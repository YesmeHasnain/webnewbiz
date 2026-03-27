import { useState, useRef } from 'react';
import { projectService } from '../../services/project.service';

interface Props {
  projectId: number;
  onFileSelect: (path: string) => void;
}

interface SearchResult {
  file: string;
  line: number;
  text: string;
}

export default function SearchPanel({ projectId, onFileSelect }: Props) {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState<SearchResult[]>([]);
  const [loading, setLoading] = useState(false);
  const [searched, setSearched] = useState(false);
  const debounceRef = useRef<ReturnType<typeof setTimeout>>(undefined);

  const handleSearch = async (q: string) => {
    if (!q.trim()) {
      setResults([]);
      setSearched(false);
      return;
    }

    setLoading(true);
    setSearched(true);
    try {
      const res = await projectService.search(projectId, q);
      setResults(res.data.results);
    } catch {
      setResults([]);
    }
    setLoading(false);
  };

  const handleChange = (value: string) => {
    setQuery(value);
    if (debounceRef.current) clearTimeout(debounceRef.current);
    debounceRef.current = setTimeout(() => handleSearch(value), 400);
  };

  // Group results by file
  const grouped = results.reduce<Record<string, SearchResult[]>>((acc, r) => {
    (acc[r.file] = acc[r.file] || []).push(r);
    return acc;
  }, {});

  return (
    <div className="flex flex-col h-full overflow-hidden">
      <div className="p-3 border-b border-[#1a1d27]">
        <div className="relative">
          <svg className="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" strokeWidth={1.5} />
            <path strokeLinecap="round" strokeWidth={1.5} d="M21 21l-4.35-4.35" />
          </svg>
          <input
            value={query}
            onChange={(e) => handleChange(e.target.value)}
            placeholder="Search in files..."
            className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-lg pl-9 pr-3 py-2 text-sm text-white outline-none focus:border-blue-500/50 placeholder-gray-600"
            autoFocus
          />
        </div>
      </div>

      <div className="flex-1 overflow-auto">
        {loading && (
          <div className="flex items-center justify-center py-8">
            <div className="w-5 h-5 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" />
          </div>
        )}

        {!loading && searched && results.length === 0 && (
          <div className="flex items-center justify-center py-8">
            <p className="text-xs text-gray-600">No results found</p>
          </div>
        )}

        {!loading && Object.entries(grouped).map(([file, fileResults]) => (
          <div key={file} className="border-b border-[#1a1d27]/50">
            <div
              className="px-3 py-1.5 bg-[#0d0d15] flex items-center gap-2 cursor-pointer hover:bg-white/5"
              onClick={() => onFileSelect(file)}
            >
              <svg className="w-3.5 h-3.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <span className="text-xs text-blue-400 truncate">{file}</span>
              <span className="text-xs text-gray-600 ml-auto">{fileResults.length}</span>
            </div>
            {fileResults.slice(0, 5).map((r, i) => (
              <div
                key={i}
                className="px-3 py-1 pl-8 hover:bg-white/5 cursor-pointer flex items-start gap-2"
                onClick={() => onFileSelect(file)}
              >
                <span className="text-xs text-gray-600 w-8 text-right flex-shrink-0">{r.line}</span>
                <span className="text-xs text-gray-400 truncate">
                  {highlightMatch(r.text, query)}
                </span>
              </div>
            ))}
          </div>
        ))}

        {!loading && !searched && (
          <div className="flex flex-col items-center justify-center py-8 gap-2">
            <svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="8" strokeWidth={1} />
              <path strokeLinecap="round" strokeWidth={1} d="M21 21l-4.35-4.35" />
            </svg>
            <p className="text-xs text-gray-600">Search across all project files</p>
          </div>
        )}
      </div>

      {results.length > 0 && (
        <div className="px-3 py-2 border-t border-[#1a1d27]">
          <p className="text-xs text-gray-600">
            {results.length} result{results.length !== 1 ? 's' : ''} in {Object.keys(grouped).length} file{Object.keys(grouped).length !== 1 ? 's' : ''}
          </p>
        </div>
      )}
    </div>
  );
}

function highlightMatch(text: string, query: string) {
  if (!query) return text;
  const idx = text.toLowerCase().indexOf(query.toLowerCase());
  if (idx === -1) return text;
  return (
    <>
      {text.substring(0, idx)}
      <span className="bg-amber-500/30 text-amber-300">{text.substring(idx, idx + query.length)}</span>
      {text.substring(idx + query.length)}
    </>
  );
}
