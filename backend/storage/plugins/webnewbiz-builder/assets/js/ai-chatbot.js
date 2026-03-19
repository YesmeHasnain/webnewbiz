/**
 * WebnewBiz AI Chatbot Widget
 * Floating AI assistant for WP Admin & Elementor Editor
 */
(function () {
  'use strict';

  if (typeof wnbAiChat === 'undefined') {
    return;
  }

  /* ------------------------------------------------------------------ */
  /*  State                                                              */
  /* ------------------------------------------------------------------ */
  var messages = [];
  var isOpen = false;
  var isLoading = false;
  var isElementor = !!(window.elementor);
  var siteName = wnbAiChat.siteName || 'Your Website';

  /* ------------------------------------------------------------------ */
  /*  Helpers                                                            */
  /* ------------------------------------------------------------------ */
  function el(tag, attrs, children) {
    var node = document.createElement(tag);
    if (attrs) {
      Object.keys(attrs).forEach(function (k) {
        if (k === 'style' && typeof attrs[k] === 'object') {
          Object.keys(attrs[k]).forEach(function (s) {
            node.style[s] = attrs[k][s];
          });
        } else if (k === 'className') {
          node.className = attrs[k];
        } else if (k.indexOf('on') === 0) {
          node.addEventListener(k.substring(2).toLowerCase(), attrs[k]);
        } else {
          node.setAttribute(k, attrs[k]);
        }
      });
    }
    if (children) {
      if (!Array.isArray(children)) children = [children];
      children.forEach(function (c) {
        if (typeof c === 'string') {
          node.appendChild(document.createTextNode(c));
        } else if (c) {
          node.appendChild(c);
        }
      });
    }
    return node;
  }

  function svgIcon(paths, vb, w, h) {
    var ns = 'http://www.w3.org/2000/svg';
    var svg = document.createElementNS(ns, 'svg');
    svg.setAttribute('viewBox', vb || '0 0 24 24');
    svg.setAttribute('width', String(w || 24));
    svg.setAttribute('height', String(h || 24));
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('stroke-width', '2');
    svg.setAttribute('stroke-linecap', 'round');
    svg.setAttribute('stroke-linejoin', 'round');
    paths.forEach(function (d) {
      var p = document.createElementNS(ns, 'path');
      p.setAttribute('d', d);
      svg.appendChild(p);
    });
    return svg;
  }

  function filledSvgIcon(html, w, h) {
    var wrapper = document.createElement('span');
    wrapper.style.display = 'inline-flex';
    wrapper.style.alignItems = 'center';
    wrapper.style.justifyContent = 'center';
    wrapper.style.width = (w || 24) + 'px';
    wrapper.style.height = (h || 24) + 'px';
    wrapper.innerHTML = html;
    return wrapper;
  }

  function sparkleIconSvg(size) {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="currentColor">' +
      '<path d="M12 2L13.09 8.26L18 6L14.74 10.91L21 12L14.74 13.09L18 18L13.09 15.74L12 22L10.91 15.74L6 18L9.26 13.09L3 12L9.26 10.91L6 6L10.91 8.26L12 2Z"/>' +
      '</svg>';
  }

  /* ------------------------------------------------------------------ */
  /*  DOM references                                                     */
  /* ------------------------------------------------------------------ */
  var root, fab, panel, messagesContainer, inputArea, textarea;

  /* ------------------------------------------------------------------ */
  /*  Build the FAB (floating action button)                             */
  /* ------------------------------------------------------------------ */
  function buildFab() {
    fab = el('button', {
      style: {
        position: 'fixed',
        bottom: '24px',
        right: '24px',
        width: '56px',
        height: '56px',
        borderRadius: '16px',
        border: 'none',
        background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
        color: '#fff',
        cursor: 'pointer',
        zIndex: '999999',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        boxShadow: '0 4px 16px rgba(124,92,252,0.4)',
        animation: 'wnb-chat-pulse 2s ease-in-out infinite',
        transition: 'transform 0.2s ease',
        padding: '0',
        outline: 'none'
      },
      onClick: togglePanel,
      title: 'AI Assistant'
    });
    fab.innerHTML = sparkleIconSvg(26);

    fab.addEventListener('mouseenter', function () { fab.style.transform = 'scale(1.08)'; });
    fab.addEventListener('mouseleave', function () { fab.style.transform = 'scale(1)'; });

    return fab;
  }

  /* ------------------------------------------------------------------ */
  /*  Build the chat panel                                               */
  /* ------------------------------------------------------------------ */
  function buildPanel() {
    panel = el('div', {
      style: {
        position: 'fixed',
        bottom: '90px',
        right: '24px',
        width: '400px',
        height: '600px',
        maxHeight: 'calc(100vh - 120px)',
        borderRadius: '16px',
        background: '#fff',
        boxShadow: '0 12px 48px rgba(0,0,0,0.18)',
        zIndex: '999999',
        display: 'none',
        flexDirection: 'column',
        overflow: 'hidden',
        animation: 'wnb-chat-slide-up 0.3s ease forwards',
        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, sans-serif'
      }
    });

    /* --- header --- */
    var header = buildHeader();
    panel.appendChild(header);

    /* --- messages area --- */
    messagesContainer = el('div', {
      style: {
        flex: '1',
        overflowY: 'auto',
        padding: '16px',
        display: 'flex',
        flexDirection: 'column',
        gap: '12px'
      }
    });
    panel.appendChild(messagesContainer);

    /* --- input area --- */
    inputArea = buildInputArea();
    panel.appendChild(inputArea);

    return panel;
  }

  function buildHeader() {
    var header = el('div', {
      style: {
        background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
        color: '#fff',
        padding: '16px 16px 14px 16px',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        flexShrink: '0'
      }
    });

    var left = el('div', {
      style: { display: 'flex', flexDirection: 'column' }
    }, [
      el('span', {
        style: { fontWeight: '700', fontSize: '16px', lineHeight: '1.2' }
      }, 'AI Assistant'),
      el('span', {
        style: { fontSize: '12px', opacity: '0.85', marginTop: '2px' }
      }, siteName)
    ]);

    var rightBtns = el('div', {
      style: { display: 'flex', gap: '8px', alignItems: 'center' }
    });

    /* clear button */
    var clearBtn = el('button', {
      style: {
        background: 'rgba(255,255,255,0.2)',
        border: 'none',
        borderRadius: '8px',
        color: '#fff',
        cursor: 'pointer',
        padding: '6px 10px',
        fontSize: '12px',
        fontWeight: '500',
        display: 'flex',
        alignItems: 'center',
        gap: '4px',
        transition: 'background 0.2s'
      },
      onClick: clearChat,
      title: 'Clear chat'
    }, 'Clear');
    clearBtn.addEventListener('mouseenter', function () { clearBtn.style.background = 'rgba(255,255,255,0.3)'; });
    clearBtn.addEventListener('mouseleave', function () { clearBtn.style.background = 'rgba(255,255,255,0.2)'; });

    /* close button */
    var closeBtn = el('button', {
      style: {
        background: 'rgba(255,255,255,0.2)',
        border: 'none',
        borderRadius: '8px',
        width: '32px',
        height: '32px',
        color: '#fff',
        cursor: 'pointer',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '0',
        fontSize: '18px',
        lineHeight: '1',
        transition: 'background 0.2s'
      },
      onClick: togglePanel,
      title: 'Close'
    });
    closeBtn.innerHTML = '&#x2715;';
    closeBtn.addEventListener('mouseenter', function () { closeBtn.style.background = 'rgba(255,255,255,0.3)'; });
    closeBtn.addEventListener('mouseleave', function () { closeBtn.style.background = 'rgba(255,255,255,0.2)'; });

    rightBtns.appendChild(clearBtn);
    rightBtns.appendChild(closeBtn);
    header.appendChild(left);
    header.appendChild(rightBtns);

    return header;
  }

  function buildInputArea() {
    var wrapper = el('div', {
      style: {
        borderTop: '1px solid #E5E7EB',
        padding: '12px 16px',
        flexShrink: '0'
      }
    });

    var inputRow = el('div', {
      style: {
        display: 'flex',
        gap: '8px',
        alignItems: 'flex-end'
      }
    });

    textarea = el('textarea', {
      style: {
        flex: '1',
        resize: 'none',
        border: '1px solid #D1D5DB',
        borderRadius: '12px',
        padding: '10px 14px',
        fontSize: '14px',
        lineHeight: '1.5',
        fontFamily: 'inherit',
        outline: 'none',
        maxHeight: '100px',
        minHeight: '42px',
        transition: 'border-color 0.2s',
        boxSizing: 'border-box'
      },
      placeholder: 'Ask anything about your website...',
      rows: '1'
    });
    textarea.addEventListener('focus', function () { textarea.style.borderColor = '#7c5cfc'; });
    textarea.addEventListener('blur', function () { textarea.style.borderColor = '#D1D5DB'; });
    textarea.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });
    textarea.addEventListener('input', autoGrow);

    var sendBtn = el('button', {
      style: {
        width: '40px',
        height: '40px',
        borderRadius: '50%',
        border: 'none',
        background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
        color: '#fff',
        cursor: 'pointer',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        flexShrink: '0',
        padding: '0',
        transition: 'opacity 0.2s'
      },
      onClick: sendMessage,
      title: 'Send message'
    });
    /* arrow up icon */
    sendBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>';
    sendBtn.addEventListener('mouseenter', function () { sendBtn.style.opacity = '0.85'; });
    sendBtn.addEventListener('mouseleave', function () { sendBtn.style.opacity = '1'; });

    inputRow.appendChild(textarea);
    inputRow.appendChild(sendBtn);
    wrapper.appendChild(inputRow);

    var hint = el('div', {
      style: {
        fontSize: '11px',
        color: '#9CA3AF',
        marginTop: '6px',
        textAlign: 'center'
      }
    }, 'Press Enter to send, Shift+Enter for new line');
    wrapper.appendChild(hint);

    return wrapper;
  }

  function autoGrow() {
    textarea.style.height = 'auto';
    textarea.style.height = Math.min(textarea.scrollHeight, 100) + 'px';
  }

  /* ------------------------------------------------------------------ */
  /*  Quick prompt cards                                                 */
  /* ------------------------------------------------------------------ */
  function buildQuickPrompts() {
    var prompts = [
      { label: 'Write Content', icon: 'pencil', msg: 'Help me write engaging content for my website.' },
      { label: 'Improve SEO', icon: 'search', msg: 'How can I improve the SEO of my website?' },
      { label: 'Optimize Speed', icon: 'bolt', msg: 'What can I do to optimize my website speed?' },
      { label: 'Generate Ideas', icon: 'lightbulb', msg: 'Generate some creative ideas for my website.' }
    ];

    if (isElementor) {
      prompts.push(
        { label: 'Edit Heading', icon: 'heading', msg: 'Help me edit the heading text on this page.' },
        { label: 'Change Colors', icon: 'palette', msg: 'Suggest a better color scheme for this page.' }
      );
    }

    var grid = el('div', {
      style: {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        gap: '8px',
        animation: 'wnb-chat-fade-in 0.4s ease forwards'
      }
    });

    prompts.forEach(function (p) {
      var card = el('button', {
        style: {
          background: '#F9FAFB',
          border: '1px solid #E5E7EB',
          borderRadius: '12px',
          padding: '12px 10px',
          cursor: 'pointer',
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: '6px',
          fontSize: '12px',
          fontWeight: '500',
          color: '#374151',
          fontFamily: 'inherit',
          transition: 'all 0.2s',
          textAlign: 'center',
          lineHeight: '1.3'
        },
        onClick: function () {
          addUserMessage(p.msg);
          sendToServer(p.msg);
        }
      });
      card.addEventListener('mouseenter', function () {
        card.style.borderColor = '#7c5cfc';
        card.style.background = '#F5F3FF';
      });
      card.addEventListener('mouseleave', function () {
        card.style.borderColor = '#E5E7EB';
        card.style.background = '#F9FAFB';
      });

      var iconEl = el('span', {
        style: {
          width: '28px',
          height: '28px',
          borderRadius: '8px',
          background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
          color: '#fff',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          fontSize: '14px'
        }
      });
      iconEl.innerHTML = getPromptIcon(p.icon);

      card.appendChild(iconEl);
      card.appendChild(document.createTextNode(p.label));
      grid.appendChild(card);
    });

    return grid;
  }

  function getPromptIcon(name) {
    var icons = {
      pencil: '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>',
      search: '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>',
      bolt: '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 10 10-12h-9l1-10z"/></svg>',
      lightbulb: '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg>',
      heading: '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4v16"/><path d="M18 4v16"/><path d="M6 12h12"/></svg>',
      palette: '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r="0.5" fill="currentColor"/><circle cx="17.5" cy="10.5" r="0.5" fill="currentColor"/><circle cx="8.5" cy="7.5" r="0.5" fill="currentColor"/><circle cx="6.5" cy="12.5" r="0.5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.93 0 1.5-.66 1.5-1.5 0-.38-.15-.74-.39-1.04-.23-.29-.38-.63-.38-1.01 0-.83.67-1.5 1.5-1.5H16c3.31 0 6-2.69 6-6 0-5.17-4.49-9-10-9z"/></svg>'
    };
    return icons[name] || '';
  }

  /* ------------------------------------------------------------------ */
  /*  Message rendering                                                  */
  /* ------------------------------------------------------------------ */
  function renderWelcome() {
    messagesContainer.innerHTML = '';

    /* AI welcome message */
    var welcomeWrap = el('div', {
      style: {
        display: 'flex',
        gap: '8px',
        alignItems: 'flex-start',
        animation: 'wnb-chat-fade-in 0.3s ease forwards'
      }
    });

    var avatar = buildAiAvatar();
    var bubble = el('div', {
      style: {
        background: '#F3F4F6',
        borderRadius: '12px 12px 12px 4px',
        padding: '12px 14px',
        fontSize: '14px',
        lineHeight: '1.5',
        color: '#374151',
        maxWidth: '85%'
      }
    }, "Hi! I'm your AI assistant. I can help you with content, SEO, performance, and more. Ask me anything!");

    welcomeWrap.appendChild(avatar);
    welcomeWrap.appendChild(bubble);
    messagesContainer.appendChild(welcomeWrap);

    /* quick prompts */
    var prompts = buildQuickPrompts();
    messagesContainer.appendChild(prompts);
  }

  function buildAiAvatar() {
    var avatar = el('div', {
      style: {
        width: '28px',
        height: '28px',
        minWidth: '28px',
        borderRadius: '8px',
        background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
        color: '#fff',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        flexShrink: '0'
      }
    });
    avatar.innerHTML = sparkleIconSvg(14);
    return avatar;
  }

  function addUserMessage(text) {
    messages.push({ role: 'user', content: text });

    /* remove quick prompts grid if still present */
    var grids = messagesContainer.querySelectorAll('div');
    /* We'll just re-render to be safe */
    renderMessages();
  }

  function addAiMessage(text) {
    messages.push({ role: 'ai', content: text });
    renderMessages();
  }

  function renderMessages() {
    messagesContainer.innerHTML = '';

    messages.forEach(function (msg, i) {
      var wrap = el('div', {
        style: {
          display: 'flex',
          gap: '8px',
          alignItems: msg.role === 'user' ? 'flex-end' : 'flex-start',
          justifyContent: msg.role === 'user' ? 'flex-end' : 'flex-start',
          animation: 'wnb-chat-fade-in 0.3s ease forwards'
        }
      });

      if (msg.role === 'ai') {
        var avatar = buildAiAvatar();
        var bubble = el('div', {
          style: {
            background: '#F3F4F6',
            borderRadius: '12px 12px 12px 4px',
            padding: '12px 14px',
            fontSize: '14px',
            lineHeight: '1.5',
            color: '#374151',
            maxWidth: '85%',
            wordWrap: 'break-word',
            whiteSpace: 'pre-wrap'
          }
        }, msg.content);
        wrap.appendChild(avatar);
        wrap.appendChild(bubble);
      } else {
        var bubble = el('div', {
          style: {
            background: 'linear-gradient(135deg, #7c5cfc 0%, #6a4ae8 100%)',
            borderRadius: '12px 12px 4px 12px',
            padding: '12px 14px',
            fontSize: '14px',
            lineHeight: '1.5',
            color: '#fff',
            maxWidth: '85%',
            wordWrap: 'break-word',
            whiteSpace: 'pre-wrap'
          }
        }, msg.content);
        wrap.appendChild(bubble);
      }

      messagesContainer.appendChild(wrap);
    });

    scrollToBottom();
  }

  /* ------------------------------------------------------------------ */
  /*  Typing indicator                                                   */
  /* ------------------------------------------------------------------ */
  function showTypingIndicator() {
    var wrap = el('div', {
      id: 'wnb-typing-indicator',
      style: {
        display: 'flex',
        gap: '8px',
        alignItems: 'flex-start',
        animation: 'wnb-chat-fade-in 0.3s ease forwards'
      }
    });

    var avatar = buildAiAvatar();
    var dots = el('div', {
      style: {
        background: '#F3F4F6',
        borderRadius: '12px 12px 12px 4px',
        padding: '12px 16px',
        display: 'flex',
        gap: '4px',
        alignItems: 'center'
      }
    });

    for (var i = 0; i < 3; i++) {
      var dot = el('span', {
        style: {
          width: '7px',
          height: '7px',
          borderRadius: '50%',
          background: '#9CA3AF',
          display: 'inline-block',
          animation: 'wnb-chat-bounce 1.2s ease-in-out infinite',
          animationDelay: (i * 0.15) + 's'
        }
      });
      dots.appendChild(dot);
    }

    wrap.appendChild(avatar);
    wrap.appendChild(dots);
    messagesContainer.appendChild(wrap);
    scrollToBottom();
  }

  function hideTypingIndicator() {
    var indicator = document.getElementById('wnb-typing-indicator');
    if (indicator) {
      indicator.parentNode.removeChild(indicator);
    }
  }

  /* ------------------------------------------------------------------ */
  /*  Scroll                                                             */
  /* ------------------------------------------------------------------ */
  function scrollToBottom() {
    requestAnimationFrame(function () {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });
  }

  /* ------------------------------------------------------------------ */
  /*  AJAX communication                                                 */
  /* ------------------------------------------------------------------ */
  function sendMessage() {
    var text = textarea.value.trim();
    if (!text || isLoading) return;

    textarea.value = '';
    textarea.style.height = 'auto';
    addUserMessage(text);
    sendToServer(text);
  }

  /**
   * Detect if the instruction is an edit request (not just a question).
   */
  function isEditRequest(text) {
    var editWords = [
      'change', 'update', 'replace', 'edit', 'modify', 'set', 'make',
      'rewrite', 'rename', 'fix', 'add', 'remove', 'delete',
      'karo', 'kardo', 'kar do', 'badal', 'badlo', 'likh',
      'bana', 'hatao', 'daal', 'likho', 'rakh', 'hata'
    ];
    var lower = text.toLowerCase();
    for (var i = 0; i < editWords.length; i++) {
      if (lower.indexOf(editWords[i]) !== -1) return true;
    }
    return false;
  }

  /**
   * Get the current Elementor page ID.
   */
  function getElementorPageId() {
    if (window.elementor && window.elementor.config && window.elementor.config.document) {
      return window.elementor.config.document.id;
    }
    // Fallback: parse from URL
    var match = window.location.search.match(/post=(\d+)/);
    return match ? parseInt(match[1], 10) : 0;
  }

  function sendToServer(text) {
    isLoading = true;
    showTypingIndicator();

    // In Elementor: if it looks like an edit instruction, use the edit endpoint
    var useEdit = isElementor && isEditRequest(text);
    var pageId = useEdit ? getElementorPageId() : 0;

    var formData = new FormData();
    formData.append('nonce', wnbAiChat.nonce);

    if (useEdit && pageId) {
      formData.append('action', 'wnb_ai_edit');
      formData.append('page_id', pageId);
      formData.append('instruction', text);
    } else {
      formData.append('action', 'wnb_ai_chat');
      formData.append('message', text);
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', wnbAiChat.ajaxUrl, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) return;

      hideTypingIndicator();
      isLoading = false;

      if (xhr.status === 200) {
        try {
          var response = JSON.parse(xhr.responseText);
          if (response.success && response.data) {
            if (useEdit && response.data.changes > 0) {
              // Edit was applied — show confirmation & reload Elementor
              var msg = '✅ ' + response.data.message;
              if (response.data.descriptions && response.data.descriptions.length) {
                msg += '\n\nChanges:\n• ' + response.data.descriptions.join('\n• ');
              }
              addAiMessage(msg);

              // Reload Elementor editor after a brief delay
              setTimeout(function () {
                if (window.$e && typeof $e.run === 'function') {
                  try {
                    $e.run('document/save/discard');
                    setTimeout(function () { location.reload(); }, 500);
                  } catch (err) {
                    location.reload();
                  }
                } else {
                  location.reload();
                }
              }, 1500);
            } else if (useEdit && response.data.changes === 0) {
              addAiMessage(response.data.message || 'Could not find matching elements to change.');
            } else if (response.data.content) {
              addAiMessage(response.data.content);
            } else {
              addAiMessage(response.data.message || 'Done!');
            }
          } else {
            var errMsg = (response.data && response.data.message) ? response.data.message : 'Sorry, I couldn\'t process that. Please try again.';
            addAiMessage(errMsg);
          }
        } catch (e) {
          addAiMessage('Sorry, I couldn\'t process that. Please try again.');
        }
      } else {
        addAiMessage('Sorry, I couldn\'t process that. Please try again.');
      }
    };
    xhr.onerror = function () {
      hideTypingIndicator();
      isLoading = false;
      addAiMessage('Connection error. Please try again.');
    };
    xhr.send(formData);
  }

  /* ------------------------------------------------------------------ */
  /*  Toggle & clear                                                     */
  /* ------------------------------------------------------------------ */
  function togglePanel() {
    isOpen = !isOpen;
    if (isOpen) {
      panel.style.display = 'flex';
      /* Re-trigger animation */
      panel.style.animation = 'none';
      /* Force reflow */
      void panel.offsetHeight;
      panel.style.animation = 'wnb-chat-slide-up 0.3s ease forwards';

      fab.style.animation = 'none';
      fab.style.boxShadow = '0 4px 16px rgba(124,92,252,0.4)';

      if (messages.length === 0) {
        renderWelcome();
      }
      textarea.focus();
    } else {
      panel.style.display = 'none';
      fab.style.animation = 'wnb-chat-pulse 2s ease-in-out infinite';
    }
  }

  function clearChat() {
    messages = [];
    renderWelcome();
  }

  /* ------------------------------------------------------------------ */
  /*  Responsive                                                         */
  /* ------------------------------------------------------------------ */
  function applyResponsive() {
    if (!panel) return;
    if (window.innerWidth < 480) {
      panel.style.bottom = '8px';
      panel.style.right = '8px';
      panel.style.left = '8px';
      panel.style.width = 'auto';
      panel.style.height = 'auto';
      panel.style.top = '8px';
      panel.style.maxHeight = 'none';
      panel.style.borderRadius = '12px';
    } else {
      panel.style.bottom = '90px';
      panel.style.right = '24px';
      panel.style.left = 'auto';
      panel.style.width = '400px';
      panel.style.height = '600px';
      panel.style.top = 'auto';
      panel.style.maxHeight = 'calc(100vh - 120px)';
      panel.style.borderRadius = '16px';
    }
  }

  /* ------------------------------------------------------------------ */
  /*  Keyboard: ESC to close                                             */
  /* ------------------------------------------------------------------ */
  function handleKeydown(e) {
    if (e.key === 'Escape' && isOpen) {
      togglePanel();
    }
  }

  /* ------------------------------------------------------------------ */
  /*  Init                                                               */
  /* ------------------------------------------------------------------ */
  function init() {
    root = el('div', { id: 'wnb-ai-chatbot' });

    root.appendChild(buildFab());
    root.appendChild(buildPanel());

    document.body.appendChild(root);

    window.addEventListener('resize', applyResponsive);
    document.addEventListener('keydown', handleKeydown);

    applyResponsive();
  }

  /* Wait for DOM ready */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
