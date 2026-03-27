import { useEffect, useRef, useState, useCallback } from 'react';
import { Terminal as XTerm } from '@xterm/xterm';
import { FitAddon } from '@xterm/addon-fit';
import '@xterm/xterm/css/xterm.css';

interface Props {
  projectId: number;
  onCommand: (command: string) => Promise<{ output: string; exit_code: number }>;
}

export default function Terminal({ projectId, onCommand }: Props) {
  const termRef = useRef<HTMLDivElement>(null);
  const xtermRef = useRef<XTerm | null>(null);
  const fitRef = useRef<FitAddon | null>(null);
  const [, setInputBuffer] = useState('');
  const inputRef = useRef('');
  const busyRef = useRef(false);
  const historyRef = useRef<string[]>([]);
  const historyIndexRef = useRef(-1);

  const writePrompt = useCallback(() => {
    if (xtermRef.current) {
      xtermRef.current.write('\r\n\x1b[38;2;52;211;153m❯\x1b[0m ');
    }
  }, []);

  useEffect(() => {
    if (!termRef.current) return;

    const term = new XTerm({
      theme: {
        background: '#0a0a12',
        foreground: '#e4e4e7',
        cursor: '#3b82f6',
        cursorAccent: '#0a0a12',
        selectionBackground: '#3b82f680',
        black: '#18181b',
        red: '#ef4444',
        green: '#22c55e',
        yellow: '#eab308',
        blue: '#3b82f6',
        magenta: '#a855f7',
        cyan: '#06b6d4',
        white: '#e4e4e7',
        brightBlack: '#52525b',
        brightRed: '#f87171',
        brightGreen: '#4ade80',
        brightYellow: '#facc15',
        brightBlue: '#60a5fa',
        brightMagenta: '#c084fc',
        brightCyan: '#22d3ee',
        brightWhite: '#fafafa',
      },
      fontFamily: '"JetBrains Mono", "Cascadia Code", "Fira Code", monospace',
      fontSize: 13,
      lineHeight: 1.5,
      cursorBlink: true,
      cursorStyle: 'bar',
      scrollback: 5000,
      allowProposedApi: true,
    });

    const fitAddon = new FitAddon();
    term.loadAddon(fitAddon);
    term.open(termRef.current);

    setTimeout(() => fitAddon.fit(), 10);

    xtermRef.current = term;
    fitRef.current = fitAddon;

    // Welcome message
    term.writeln('\x1b[38;2;59;130;246m╭─────────────────────────────────────╮\x1b[0m');
    term.writeln('\x1b[38;2;59;130;246m│\x1b[0m  \x1b[1;37mWebNewBiz Terminal\x1b[0m                \x1b[38;2;59;130;246m│\x1b[0m');
    term.writeln('\x1b[38;2;59;130;246m│\x1b[0m  \x1b[2;37mType commands to interact with\x1b[0m    \x1b[38;2;59;130;246m│\x1b[0m');
    term.writeln('\x1b[38;2;59;130;246m│\x1b[0m  \x1b[2;37myour project. Try: ls, cat, npm\x1b[0m   \x1b[38;2;59;130;246m│\x1b[0m');
    term.writeln('\x1b[38;2;59;130;246m╰─────────────────────────────────────╯\x1b[0m');
    writePrompt();

    // Handle keyboard input
    term.onKey(({ key, domEvent }) => {
      if (busyRef.current) return;

      const code = domEvent.keyCode;

      if (code === 13) {
        // Enter
        const cmd = inputRef.current.trim();
        term.write('\r\n');

        if (cmd) {
          historyRef.current.unshift(cmd);
          historyIndexRef.current = -1;

          if (cmd === 'clear') {
            term.clear();
            writePrompt();
            inputRef.current = '';
            setInputBuffer('');
            return;
          }

          busyRef.current = true;
          term.write('\x1b[2;37mRunning...\x1b[0m');

          onCommand(cmd)
            .then(({ output, exit_code }) => {
              // Clear "Running..." line
              term.write('\r\x1b[2K');
              if (output) {
                const lines = output.split('\n');
                lines.forEach((line, i) => {
                  if (exit_code !== 0) {
                    term.write(`\x1b[31m${line}\x1b[0m`);
                  } else {
                    term.write(line);
                  }
                  if (i < lines.length - 1) term.write('\r\n');
                });
              }
              writePrompt();
            })
            .catch(() => {
              term.write('\r\x1b[2K');
              term.write('\x1b[31mCommand execution failed\x1b[0m');
              writePrompt();
            })
            .finally(() => {
              busyRef.current = false;
            });
        } else {
          writePrompt();
        }

        inputRef.current = '';
        setInputBuffer('');
      } else if (code === 8) {
        // Backspace
        if (inputRef.current.length > 0) {
          inputRef.current = inputRef.current.slice(0, -1);
          setInputBuffer(inputRef.current);
          term.write('\b \b');
        }
      } else if (code === 38) {
        // Arrow up - history
        if (historyRef.current.length > 0) {
          historyIndexRef.current = Math.min(
            historyIndexRef.current + 1,
            historyRef.current.length - 1
          );
          const cmd = historyRef.current[historyIndexRef.current];
          // Clear current input
          term.write('\r\x1b[2K\x1b[38;2;52;211;153m❯\x1b[0m ' + cmd);
          inputRef.current = cmd;
          setInputBuffer(cmd);
        }
      } else if (code === 40) {
        // Arrow down - history
        if (historyIndexRef.current > 0) {
          historyIndexRef.current--;
          const cmd = historyRef.current[historyIndexRef.current];
          term.write('\r\x1b[2K\x1b[38;2;52;211;153m❯\x1b[0m ' + cmd);
          inputRef.current = cmd;
          setInputBuffer(cmd);
        } else {
          historyIndexRef.current = -1;
          term.write('\r\x1b[2K\x1b[38;2;52;211;153m❯\x1b[0m ');
          inputRef.current = '';
          setInputBuffer('');
        }
      } else if (domEvent.ctrlKey && code === 67) {
        // Ctrl+C
        term.write('^C');
        inputRef.current = '';
        setInputBuffer('');
        writePrompt();
      } else if (domEvent.ctrlKey && code === 76) {
        // Ctrl+L - clear
        term.clear();
        writePrompt();
        inputRef.current = '';
        setInputBuffer('');
      } else if (key.length === 1 && !domEvent.ctrlKey && !domEvent.altKey) {
        // Regular character
        inputRef.current += key;
        setInputBuffer(inputRef.current);
        term.write(key);
      }
    });

    // Handle paste
    term.onData((data) => {
      if (busyRef.current) return;
      // Only handle paste (multi-char data that's not from onKey)
      if (data.length > 1 && !data.startsWith('\x1b')) {
        inputRef.current += data;
        setInputBuffer(inputRef.current);
        xtermRef.current?.write(data);
      }
    });

    // Handle resize
    const resizeObserver = new ResizeObserver(() => {
      try { fitAddon.fit(); } catch {}
    });
    resizeObserver.observe(termRef.current);

    return () => {
      resizeObserver.disconnect();
      term.dispose();
    };
  }, [projectId, onCommand, writePrompt]);

  return (
    <div
      ref={termRef}
      className="w-full h-full"
      style={{ padding: '8px 0 0 8px' }}
    />
  );
}
