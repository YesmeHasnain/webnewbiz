import React, { useState, useCallback, useEffect, useRef } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import api from '../services/api';
import { websiteService } from '../services/website.service';

/* ── Types ── */
interface Section { type: string; label: string; prompt?: string }
interface Page { title: string; slug: string; sections: Section[] }

/* ── Section Types for Add Panel ── */
const SECTION_TYPES: { type: string; label: string; desc: string }[] = [
  { type: 'custom', label: 'Custom section', desc: 'Describe any section you want' },
  { type: 'hero', label: 'Hero section', desc: 'Highlight the main message' },
  { type: 'logos', label: 'Logos section', desc: 'Display logos of key customers' },
  { type: 'cta', label: 'Call to Action section', desc: 'Urge users to take action' },
  { type: 'about_preview', label: 'About section', desc: 'Provide info about the company' },
  { type: 'gallery', label: 'Gallery section', desc: 'Showcase images or media' },
  { type: 'content', label: 'Banner section', desc: 'Display promotional banner' },
  { type: 'stats', label: 'Statistics section', desc: 'Display key statistics' },
  { type: 'features', label: 'Feature section', desc: 'Highlight a feature in details' },
  { type: 'features', label: 'Features list section', desc: 'Highlight core features' },
  { type: 'testimonials', label: 'Testimonials section', desc: 'Show customer reviews' },
  { type: 'pricing', label: 'Pricing section', desc: 'Display pricing plans' },
  { type: 'team', label: 'Team section', desc: 'Introduce team members' },
  { type: 'faq', label: 'FAQ section', desc: 'Answer common questions' },
  { type: 'contact_form', label: 'Contact section', desc: 'Contact form for visitors' },
  { type: 'process', label: 'Process section', desc: 'Show step-by-step process' },
];

export default function StructureEditor() {
  const nav = useNavigate();
  const loc = useLocation();

  // Get data from WebsiteGenerator via location state
  const initData = loc.state as { pages: Page[]; businessName: string; businessType: string; prompt: string; theme?: string } | null;

  const [pages, setPages] = useState<Page[]>(initData?.pages || []);
  const [bName] = useState(initData?.businessName || '');
  const [bType] = useState(initData?.businessType || '');
  const [userPrompt] = useState(initData?.prompt || '');
  const [theme] = useState(initData?.theme || 'azure');

  // UI state
  const [selectedSection, setSelectedSection] = useState<{ pageIdx: number; secIdx: number } | null>(null);
  const [sidebarMode, setSidebarMode] = useState<'edit' | 'add' | null>(null);
  const [addTarget, setAddTarget] = useState<{ pageIdx: number; afterIdx: number } | null>(null);
  const [hoveredSec, setHoveredSec] = useState<{ pi: number; si: number } | null>(null);
  const [previewSection, setPreviewSection] = useState<{ type: string; label: string } | null>(null);
  const [pageMenu, setPageMenu] = useState<number | null>(null);
  const [showAddPage, setShowAddPage] = useState(false);
  const [newPageName, setNewPageName] = useState('');
  const [newPageLoading, setNewPageLoading] = useState(false);
  const [editingPageIdx, setEditingPageIdx] = useState<number | null>(null);
  const [editingPageName, setEditingPageName] = useState('');
  const [zoom, setZoom] = useState(100);
  const [building, setBuilding] = useState(false);
  const [history, setHistory] = useState<Page[][]>([]);
  const [historyIdx, setHistoryIdx] = useState(-1);
  const [enhancing, setEnhancing] = useState(false);
  const [dragState, setDragState] = useState<{ pi: number; si: number } | null>(null);
  const [dragOver, setDragOver] = useState<{ pi: number; si: number } | null>(null);
  const [visibleCards, setVisibleCards] = useState(0);
  const [revealedCards, setRevealedCards] = useState<Set<number>>(new Set());
  const [initialRevealDone, setInitialRevealDone] = useState(false);
  const [revealedSections, setRevealedSections] = useState<Record<number, number>>({}); // cardIdx -> number of visible sections

  // Stagger card reveal - show card with skeleton first, then reveal sections
  useEffect(() => {
    if (initialRevealDone || pages.length === 0) return;
    if (visibleCards >= pages.length) {
      setInitialRevealDone(true);
      return;
    }
    const delay = visibleCards === 0 ? 300 : 400;
    const t = setTimeout(() => setVisibleCards(v => v + 1), delay);
    return () => clearTimeout(t);
  }, [pages.length, visibleCards, initialRevealDone]);

  // After each card appears, reveal it then stagger sections
  useEffect(() => {
    if (visibleCards === 0) return;
    const timers: ReturnType<typeof setTimeout>[] = [];
    for (let i = 0; i < visibleCards; i++) {
      if (!revealedCards.has(i)) {
        const t = setTimeout(() => {
          setRevealedCards(prev => new Set([...prev, i]));
          setRevealedSections(prev => ({ ...prev, [i]: 0 }));
        }, 600 + i * 200);
        timers.push(t);
      }
    }
    return () => timers.forEach(t => clearTimeout(t));
  }, [visibleCards]);

  // Stagger individual sections within each revealed card
  useEffect(() => {
    const timers: ReturnType<typeof setTimeout>[] = [];
    revealedCards.forEach(cardIdx => {
      const totalSections = pages[cardIdx]?.sections?.length || 0;
      const shown = revealedSections[cardIdx] ?? 0;
      if (shown < totalSections) {
        const t = setTimeout(() => {
          setRevealedSections(prev => ({ ...prev, [cardIdx]: (prev[cardIdx] ?? 0) + 1 }));
        }, 80);
        timers.push(t);
      }
    });
    return () => timers.forEach(t => clearTimeout(t));
  }, [revealedCards, revealedSections, pages]);

  // Push to history on changes
  const pushHistory = useCallback((newPages: Page[]) => {
    setHistory(prev => [...prev.slice(0, historyIdx + 1), newPages]);
    setHistoryIdx(prev => prev + 1);
  }, [historyIdx]);

  const undo = () => {
    if (historyIdx > 0) {
      setHistoryIdx(historyIdx - 1);
      setPages(history[historyIdx - 1]);
    }
  };
  const redo = () => {
    if (historyIdx < history.length - 1) {
      setHistoryIdx(historyIdx + 1);
      setPages(history[historyIdx + 1]);
    }
  };

  // Init history
  useEffect(() => {
    if (pages.length > 0 && history.length === 0) {
      setHistory([pages]);
      setHistoryIdx(0);
    }
  }, [pages, history.length]);

  const updatePages = (newPages: Page[]) => {
    setPages(newPages);
    pushHistory(newPages);
  };

  // Section actions
  const deleteSection = (pi: number, si: number) => {
    const np = pages.map((p, i) => i === pi ? { ...p, sections: p.sections.filter((_, j) => j !== si) } : p);
    updatePages(np);
    setSelectedSection(null); setSidebarMode(null);
  };

  const updateSectionTitle = (pi: number, si: number, title: string) => {
    const np = pages.map((p, i) => i === pi ? { ...p, sections: p.sections.map((s, j) => j === si ? { ...s, label: title } : s) } : p);
    setPages(np); // don't push every keystroke
  };

  const updateSectionPrompt = (pi: number, si: number, prompt: string) => {
    const np = pages.map((p, i) => i === pi ? { ...p, sections: p.sections.map((s, j) => j === si ? { ...s, prompt } : s) } : p);
    setPages(np);
  };

  const addSection = (pi: number, afterIdx: number, type: string, label: string) => {
    const np = pages.map((p, i) => {
      if (i !== pi) return p;
      const secs = [...p.sections];
      secs.splice(afterIdx + 1, 0, { type, label, prompt: '' });
      return { ...p, sections: secs };
    });
    updatePages(np);
    setPreviewSection(null);
    // Select new section for editing
    setSelectedSection({ pageIdx: pi, secIdx: afterIdx + 1 });
    setSidebarMode('edit');
    setAddTarget(null);
  };

  // Page actions
  const deletePage = (pi: number) => {
    updatePages(pages.filter((_, i) => i !== pi));
    setPageMenu(null);
  };

  const renamePage = (pi: number, name: string) => {
    const np = pages.map((p, i) => i === pi ? { ...p, title: name, slug: name.toLowerCase().replace(/[^a-z0-9]+/g, '-') } : p);
    updatePages(np);
    setEditingPageIdx(null); setPageMenu(null);
  };

  const addNewPage = async () => {
    if (!newPageName.trim()) return;
    setNewPageLoading(true);
    const tempPage: Page = { title: newPageName, slug: newPageName.toLowerCase().replace(/[^a-z0-9]+/g, '-'), sections: [] };
    const newPages = [...pages, tempPage];
    setPages(newPages);
    setVisibleCards(newPages.length); // show new page immediately
    setShowAddPage(false);

    try {
      const { data } = await api.post('/builder/generate-page-sections', {
        page_name: newPageName,
        business_name: bName,
        business_type: bType,
        prompt: userPrompt,
      });
      const finalPages = newPages.map((p, i) =>
        i === newPages.length - 1 ? { ...p, sections: data.sections || [{ type: 'hero', label: 'Header' }, { type: 'content', label: newPageName }, { type: 'content', label: 'Footer' }] } : p
      );
      updatePages(finalPages);
    } catch {
      const finalPages = newPages.map((p, i) =>
        i === newPages.length - 1 ? { ...p, sections: [{ type: 'hero', label: 'Header' }, { type: 'content', label: newPageName + ' Content' }, { type: 'content', label: 'Footer' }] } : p
      );
      updatePages(finalPages);
    }
    setNewPageLoading(false); setNewPageName('');
  };

  // Enhance section prompt with AI
  const enhancePrompt = async () => {
    if (!sel || !selSection || enhancing) return;
    setEnhancing(true);
    try {
      const { data } = await api.post('/builder/enhance-prompt', {
        prompt: `Enhance this section prompt for a ${selSection.type} section titled "${selSection.label}" on a ${bType} website called "${bName}": ${selSection.prompt || selSection.label}`,
      });
      if (data.success && data.enhanced) {
        updateSectionPrompt(sel.pageIdx, sel.secIdx, data.enhanced);
        pushHistory(pages);
      }
    } catch { /* ignore */ }
    setEnhancing(false);
  };

  // Drag handlers
  const onDragStart = (pi: number, si: number) => setDragState({ pi, si });
  const onDragOver = (pi: number, si: number, e: React.DragEvent) => { e.preventDefault(); setDragOver({ pi, si }); };
  const onDrop = (pi: number, si: number) => {
    if (!dragState || dragState.pi !== pi) { setDragState(null); setDragOver(null); return; }
    const np = pages.map((p, i) => {
      if (i !== pi) return p;
      const secs = [...p.sections];
      const [moved] = secs.splice(dragState.si, 1);
      secs.splice(si, 0, moved);
      return { ...p, sections: secs };
    });
    updatePages(np);
    setDragState(null); setDragOver(null);
  };

  // Build
  const saveAndBuild = async () => {
    setBuilding(true);
    try {
      const res = await websiteService.generate({
        business_name: bName, business_type: bType,
        prompt: userPrompt, layout: theme,
        pages: pages.map(p => p.slug),
        structure: pages, // send full structure with sections
      });
      nav(`/builder/progress/${res.data.id}`);
    } catch { setBuilding(false); }
  };

  const homePage = pages[0];
  const otherPages = pages.slice(1);
  // Pan with spacebar + mouse drag
  const [isPanning, setIsPanning] = useState(false);
  const [spaceDown, setSpaceDown] = useState(false);
  const panStart = useRef({ x: 0, y: 0, scrollX: 0, scrollY: 0 });
  const canvasRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const onKeyDown = (e: KeyboardEvent) => { if (e.code === 'Space' && !e.repeat) { e.preventDefault(); setSpaceDown(true); } };
    const onKeyUp = (e: KeyboardEvent) => { if (e.code === 'Space') { setSpaceDown(false); setIsPanning(false); } };
    window.addEventListener('keydown', onKeyDown);
    window.addEventListener('keyup', onKeyUp);
    return () => { window.removeEventListener('keydown', onKeyDown); window.removeEventListener('keyup', onKeyUp); };
  }, []);

  const onCanvasMouseDown = (e: React.MouseEvent) => {
    if (spaceDown && canvasRef.current) {
      setIsPanning(true);
      panStart.current = { x: e.clientX, y: e.clientY, scrollX: canvasRef.current.scrollLeft, scrollY: canvasRef.current.scrollTop };
    }
  };
  const onCanvasMouseMove = (e: React.MouseEvent) => {
    if (isPanning && canvasRef.current) {
      canvasRef.current.scrollLeft = panStart.current.scrollX - (e.clientX - panStart.current.x);
      canvasRef.current.scrollTop = panStart.current.scrollY - (e.clientY - panStart.current.y);
    }
  };
  const onCanvasMouseUp = () => setIsPanning(false);

  const sel = selectedSection;
  const selSection = sel ? pages[sel.pageIdx]?.sections[sel.secIdx] : null;

  return (
    <div className="se-root">
      {/* Top Bar */}
      <div className="se-topbar">
        <div className="se-topbar-left">
          <button className="se-icon-btn" onClick={undo} disabled={historyIdx <= 0} title="Undo">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M3 10h10a5 5 0 010 10H9"/><polyline points="7 14 3 10 7 6"/></svg>
          </button>
          <button className="se-icon-btn" onClick={redo} disabled={historyIdx >= history.length - 1} title="Redo">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M21 10H11a5 5 0 000 10h4"/><polyline points="17 14 21 10 17 6"/></svg>
          </button>
        </div>
        <div className="se-topbar-right">
          <button className="se-btn-outline" onClick={() => nav(-1)}>Discard and close</button>
          <button className="se-btn-primary" onClick={saveAndBuild} disabled={building}>
            {building ? 'Building...' : 'Save and start building'}
          </button>
        </div>
      </div>

      {/* Canvas */}
      <div className={`se-canvas ${spaceDown ? 'se-panning' : ''}`} ref={canvasRef}
        onClick={() => { setSelectedSection(null); setSidebarMode(null); setPageMenu(null); setShowAddPage(false); }}
        onMouseDown={onCanvasMouseDown} onMouseMove={onCanvasMouseMove} onMouseUp={onCanvasMouseUp} onMouseLeave={onCanvasMouseUp}>
        <div className="se-canvas-inner" style={{ transform: `scale(${zoom / 100})` }}>

          {/* Home Card */}
          {homePage && visibleCards >= 1 && (
            <div className="se-card-wrap card-enter" onClick={e => e.stopPropagation()}>
              <div className="se-card-title-bar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#555" strokeWidth="2" strokeLinecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                <strong>{homePage.title}</strong>
                <button className="se-add-sec-header" onClick={() => {
                  const footerIdx = homePage.sections.findIndex(s => s.label.toLowerCase() === 'footer');
                  const insertAfter = footerIdx > 0 ? footerIdx - 1 : homePage.sections.length - 2;
                  setAddTarget({ pageIdx: 0, afterIdx: Math.max(0, insertAfter) }); setSidebarMode('add');
                }}>+</button>
              </div>
              <div className="se-home-card">
              <div className="se-sections-list">
                {!revealedCards.has(0) ? (
                  <div className="se-skeleton-sections">
                    {[1,2,3,4,5,6].map(k => <div key={k} className="se-skel-bar" />)}
                  </div>
                ) : homePage.sections.slice(0, revealedSections[0] ?? 0).map((sec, si) => (
                  <React.Fragment key={si}>
                    <div
                      className={`se-section-row sec-enter ${sel?.pageIdx === 0 && sel?.secIdx === si ? 'selected' : ''} ${dragOver?.pi === 0 && dragOver?.si === si ? 'drag-over' : ''} ${sec.label.toLowerCase() === 'header' || sec.label.toLowerCase() === 'footer' ? 'se-faded' : ''}`}
                      draggable
                      onDragStart={() => onDragStart(0, si)}
                      onDragOver={(e) => onDragOver(0, si, e)}
                      onDrop={() => onDrop(0, si)}
                      onDragEnd={() => { setDragState(null); setDragOver(null); }}
                      onMouseEnter={() => setHoveredSec({ pi: 0, si })}
                      onMouseLeave={() => setHoveredSec(null)}
                      onClick={(e) => { e.stopPropagation(); setSelectedSection({ pageIdx: 0, secIdx: si }); setSidebarMode('edit'); }}
                    >
                      {hoveredSec?.pi === 0 && hoveredSec?.si === si && (
                        <div className="se-hover-actions">
                          <button className="se-drag-handle" onMouseDown={e => e.stopPropagation()} title="Drag to reorder">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff"><circle cx="8" cy="4" r="2"/><circle cx="16" cy="4" r="2"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/><circle cx="8" cy="20" r="2"/><circle cx="16" cy="20" r="2"/></svg>
                          </button>
                          <button className="se-add-between" onClick={(e) => { e.stopPropagation(); setAddTarget({ pageIdx: 0, afterIdx: si }); setSidebarMode('add'); }} title="Add section">+</button>
                        </div>
                      )}
                      <span className="se-section-label">{sec.label}</span>
                    </div>
                    {/* Live preview when hovering Add Sections sidebar */}
                    {previewSection && addTarget?.pageIdx === 0 && addTarget?.afterIdx === si && (
                      <div className="se-section-row se-preview-row">
                        <span className="se-section-label">{previewSection.label}</span>
                      </div>
                    )}
                  </React.Fragment>
                ))}
              </div>
            </div>
            </div>
          )}

          {/* Connector Lines + Add Page + Other Pages */}
          {visibleCards >= 1 && (
            <div className="se-tree-area">
              {/* Vertical line from Home */}
              <div className="se-tree-vline" />

              {/* Add new page button */}
              <div className="se-add-page-area" onClick={e => e.stopPropagation()}>
                <button className="se-add-page-btn" onClick={() => setShowAddPage(true)}>+ Add new page</button>
                {showAddPage && (
                  <div className="se-add-page-popup">
                    <p>Add new page title</p>
                    <input value={newPageName} onChange={e => setNewPageName(e.target.value)} placeholder="e.g. Blog" autoFocus
                      onKeyDown={e => { if (e.key === 'Enter') addNewPage(); }} />
                    <button className="se-proceed-btn" onClick={addNewPage} disabled={!newPageName.trim() || newPageLoading}>
                      {newPageLoading ? 'Creating...' : 'Proceed'}
                    </button>
                  </div>
                )}
              </div>

              {/* Horizontal line */}
              <div className="se-tree-hline" />

              {/* Other Pages Row with vertical connectors */}
              <div className="se-pages-row">
                {otherPages.map((page, idx) => {
              const pi = idx + 1;
              if (visibleCards < pi + 1) return null; // stagger reveal
              return (
                <div key={page.slug} className="se-page-col card-enter" onClick={e => e.stopPropagation()}>
                  <div className="se-tree-vline-sm" />
                  <div className="se-card-title-bar se-card-title-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#555" strokeWidth="2" strokeLinecap="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    {editingPageIdx === pi ? (
                      <input className="se-rename-input" value={editingPageName} onChange={e => setEditingPageName(e.target.value)} autoFocus
                        onBlur={() => renamePage(pi, editingPageName)}
                        onKeyDown={e => { if (e.key === 'Enter') renamePage(pi, editingPageName); }} />
                    ) : (
                      <strong>{page.title}</strong>
                    )}
                    <div style={{ position: 'relative', marginLeft: 'auto' }}>
                      <button className="se-menu-btn" onClick={(e) => { e.stopPropagation(); setPageMenu(pageMenu === pi ? null : pi); }}>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#999"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                      </button>
                      {pageMenu === pi && (
                        <div className="se-dropdown" onClick={e => e.stopPropagation()}>
                          <button onClick={() => { setEditingPageIdx(pi); setEditingPageName(page.title); setPageMenu(null); }}>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                          </button>
                          <button onClick={() => deletePage(pi)}>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            Delete
                          </button>
                        </div>
                      )}
                    </div>
                  </div>
                  <div className="se-page-card">
                  <div className="se-sections-list">
                    {(!revealedCards.has(pi) || (page.sections.length === 0 && newPageLoading)) ? (
                      <div className="se-skeleton-sections">
                        {[1,2,3,4].map(k => <div key={k} className="se-skel-bar" />)}
                      </div>
                    ) : page.sections.slice(0, revealedSections[pi] ?? 0).map((sec, si) => (
                      <React.Fragment key={si}>
                        <div
                          className={`se-section-row sec-enter ${sel?.pageIdx === pi && sel?.secIdx === si ? 'selected' : ''} ${dragOver?.pi === pi && dragOver?.si === si ? 'drag-over' : ''} ${sec.label.toLowerCase() === 'header' || sec.label.toLowerCase() === 'footer' ? 'se-faded' : ''}`}
                          draggable
                          onDragStart={() => onDragStart(pi, si)}
                          onDragOver={(e) => onDragOver(pi, si, e)}
                          onDrop={() => onDrop(pi, si)}
                          onDragEnd={() => { setDragState(null); setDragOver(null); }}
                          onMouseEnter={() => setHoveredSec({ pi, si })}
                          onMouseLeave={() => setHoveredSec(null)}
                          onClick={(e) => { e.stopPropagation(); setSelectedSection({ pageIdx: pi, secIdx: si }); setSidebarMode('edit'); }}
                        >
                          {hoveredSec?.pi === pi && hoveredSec?.si === si && (
                            <div className="se-hover-actions">
                              <button className="se-drag-handle" title="Drag"><svg width="12" height="12" viewBox="0 0 24 24" fill="#fff"><circle cx="8" cy="4" r="2"/><circle cx="16" cy="4" r="2"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/><circle cx="8" cy="20" r="2"/><circle cx="16" cy="20" r="2"/></svg></button>
                              <button className="se-add-between" onClick={(e) => { e.stopPropagation(); setAddTarget({ pageIdx: pi, afterIdx: si }); setSidebarMode('add'); }}>+</button>
                            </div>
                          )}
                          <span className="se-section-label">{sec.label}</span>
                        </div>
                        {previewSection && addTarget?.pageIdx === pi && addTarget?.afterIdx === si && (
                          <div className="se-section-row se-preview-row">
                            <span className="se-section-label">{previewSection.label}</span>
                          </div>
                        )}
                      </React.Fragment>
                    ))}
                  </div>
                </div>
                </div>
              );
            })}
          </div>
          </div>
          )}
        </div>
      </div>

      {/* Right Sidebar */}
      {sidebarMode && (
        <div className="se-sidebar" onClick={e => e.stopPropagation()}>
          {sidebarMode === 'edit' && selSection && sel && (
            <div className="se-sidebar-edit">
              <div className="se-sidebar-header">
                <h3>{selSection.type.charAt(0).toUpperCase() + selSection.type.slice(1).replace('_', ' ')} section</h3>
                <button className="se-trash-btn" onClick={() => deleteSection(sel.pageIdx, sel.secIdx)} title="Delete section">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" strokeWidth="2" strokeLinecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                </button>
              </div>
              <hr />
              <label>Title</label>
              <input value={selSection.label} onChange={e => updateSectionTitle(sel.pageIdx, sel.secIdx, e.target.value)}
                onBlur={() => pushHistory(pages)} />
              <div className="se-prompt-header">
                <label>Section prompt</label>
                <button className="se-enhance-btn" onClick={enhancePrompt} disabled={enhancing}>
                  <span className="se-green-dot" /> {enhancing ? 'Enhancing...' : 'Enhance with AI'}
                </button>
              </div>
              <textarea value={selSection.prompt || ''} onChange={e => updateSectionPrompt(sel.pageIdx, sel.secIdx, e.target.value)}
                onBlur={() => pushHistory(pages)} placeholder="Describe what this section should contain..." rows={5} />
            </div>
          )}
          {sidebarMode === 'add' && (
            <div className="se-sidebar-add">
              <h3>Add Sections</h3>
              <div className="se-section-types">
                {SECTION_TYPES.map((st, i) => (
                  <button key={i} className="se-type-item"
                    onMouseEnter={() => setPreviewSection({ type: st.type, label: st.label.replace(' section', '') })}
                    onMouseLeave={() => setPreviewSection(null)}
                    onClick={() => addTarget && addSection(addTarget.pageIdx, addTarget.afterIdx, st.type, st.label.replace(' section', ''))}>
                    <div className="se-type-info">
                      <strong>{st.label}</strong>
                      <span>{st.desc}</span>
                    </div>
                    <span className="se-type-add">+</span>
                  </button>
                ))}
              </div>
            </div>
          )}
        </div>
      )}

      {/* Zoom Controls */}
      <div className="se-zoom">
        <button onClick={() => setZoom(z => Math.max(50, z - 10))}>−</button>
        <span>{zoom}%</span>
        <button onClick={() => setZoom(z => Math.min(150, z + 10))}>+</button>
      </div>

      {/* Bottom hint */}
      <div className="se-hint">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#999" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        Spacebar + Left-click to move around
      </div>

      <style>{editorCSS}</style>
    </div>
  );
}

const editorCSS = `
.se-root { position:fixed; inset:0; background:#0003; font-family:system-ui,-apple-system,sans-serif; display:flex; flex-direction:column; padding:15px; }

/* Top Bar - inside canvas */
.se-topbar { height:50px; background:transparent; display:flex; align-items:center; justify-content:space-between; padding:0 24px; z-index:100; flex-shrink:0; position:absolute; top:24px; left:24px; right:24px; }
.se-topbar-left { display:flex; gap:8px; }
.se-topbar-right { display:flex; gap:10px; }
.se-icon-btn { width:36px; height:36px; border-radius:8px; border:none; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#666; transition:all .15s; }
.se-icon-btn:hover { background:#f0f0f0; }
.se-icon-btn:disabled { opacity:.3; cursor:not-allowed; }
.se-btn-outline { padding:10px 22px; border-radius:10px; border:1px solid #ddd; background:#fff; font-size:13px; font-weight:600; color:#333; cursor:pointer; transition:all .15s; box-shadow:0 1px 4px rgba(0,0,0,.06); }
.se-btn-outline:hover { background:#f5f5f5; }
.se-btn-primary { padding:10px 22px; border-radius:10px; border:none; background:#3b38f1; color:#fff; font-size:13px; font-weight:600; cursor:pointer; transition:all .15s; box-shadow:0 2px 8px rgba(59,56,241,.3); }
.se-btn-primary:hover { background:#3330e0; }
.se-btn-primary:disabled { opacity:.5; cursor:not-allowed; }

/* Canvas */
.se-panning { cursor:grab !important; user-select:none; }
.se-panning * { cursor:grab !important; pointer-events:none !important; }
.se-canvas { flex:1; overflow:auto; background-color:#f3f3f1; border-radius:35px; background-image:radial-gradient(circle,#ccc 1px,transparent 1px); background-size:24px 24px; position:relative; }
.se-canvas-inner { min-height:100%; padding:70px 40px 40px; display:flex; flex-direction:column; align-items:center; gap:20px; transform-origin:top center; transition:transform .2s; }

/* Home Card - white card, only sections inside */
.se-home-card { background:#fff; border-radius:10px; border:1px solid #ddd; width:340px; box-shadow:0 2px 12px rgba(0,0,0,.05); overflow:visible; }
/* Other page card */
.se-page-card { background:#fff; border-radius:10px; border:1px solid #e0e0e0; width:100%; box-shadow:0 1px 8px rgba(0,0,0,.04); overflow:visible; }
.se-page-card:hover { box-shadow:0 3px 16px rgba(0,0,0,.07); }

/* Title bar outside card - gray bg */
.se-card-title-bar { display:flex; align-items:center; gap:8px; padding:10px 16px; background:#e2e3e4; border-radius:8px; margin-bottom:6px; width:100%; }
.se-card-title-bar strong { font-size:14px; color:#333; font-weight:700; flex:1; }
.se-card-title-sm strong { font-size:13px; }
.se-card-wrap { display:flex; flex-direction:column; align-items:stretch; width:340px; }
.se-add-sec-header { width:24px; height:24px; border-radius:6px; border:1px solid #ddd; background:#fff; font-size:16px; color:#999; cursor:pointer; display:flex; align-items:center; justify-content:center; }
.se-add-sec-header:hover { border-color:#6366f1; color:#6366f1; }
.se-menu-btn { border:none; background:none; cursor:pointer; padding:4px; border-radius:4px; }
.se-menu-btn:hover { background:#eee; }

/* Sections List */
.se-sections-list { padding:10px 10px; display:flex; flex-direction:column; gap:3px; overflow:visible; position:relative; }
.se-section-row { position:relative; padding:12px 16px 12px 16px; font-size:13.5px; color:#333; cursor:pointer; transition:all .15s; background:#f6f6f6; border-radius:6px; overflow:visible; z-index:1; margin-left:0; }
.se-section-row::after { content:''; position:absolute; left:-45px; top:0; bottom:0; width:45px; }
.se-section-row:last-child { }
.se-section-row:hover { background:rgba(99,102,241,.04); z-index:10; }
.se-section-row.selected { background:rgba(99,102,241,.06); border-left:3px solid #6366f1; }
.se-section-row.drag-over { border-top:2px solid #6366f1; }
.se-section-row.se-faded .se-section-label { color:#333; }
.se-preview-row { background:rgba(99,102,241,.06) !important; border-left:3px solid #6366f1; color:#6366f1 !important; animation:previewIn .2s ease; }
.se-preview-row .se-section-label { color:#6366f1; font-style:italic; }
@keyframes previewIn { from{opacity:0;max-height:0;padding:0 20px} to{opacity:1;max-height:50px;padding:12px 20px} }
.se-section-label { pointer-events:none; }

/* Hover Actions - drag left with gap, + below section center */
.se-hover-actions { animation:fadeIn .15s ease; z-index:5; }
.se-drag-handle { position:absolute; left:-40px; top:50%; transform:translateY(-50%); width:28px; height:28px; border-radius:8px; background:#6366f1; border:none; cursor:grab; display:flex; align-items:center; justify-content:center; transition:all .15s; box-shadow:0 2px 8px rgba(99,102,241,.3); }
.se-drag-handle:hover { background:#4f46e5; transform:translateY(-50%) scale(1.08); }
.se-add-between { position:absolute; bottom:0; left:50%; transform:translate(-50%, 50%); width:26px; height:26px; border-radius:50%; background:#6366f1; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:15px; color:#fff; font-weight:700; box-shadow:0 2px 8px rgba(99,102,241,.3); transition:all .15s; z-index:20; }
.se-add-between:hover { background:#4f46e5; transform:translate(-50%, 50%) scale(1.08); }

/* Tree connectors */
.se-tree-area { display:flex; flex-direction:column; align-items:center; position:relative; }
.se-tree-vline { width:1px; height:24px; background:#d0d0d0; }
.se-tree-hline { display:none; }

/* Add Page */
.se-add-page-area { display:flex; flex-direction:column; align-items:center; position:relative; margin-bottom:0; }
.se-add-page-btn { padding:10px 24px; border-radius:20px; background:#3b38f1; color:#fff; font-size:13px; font-weight:600; border:none; cursor:pointer; transition:all .2s; }
.se-add-page-btn:hover { background:#3330e0; transform:scale(1.03); }
.se-add-page-popup { position:absolute; bottom:50px; left:50%; transform:translateX(-50%); background:#fff; border-radius:12px; padding:20px; box-shadow:0 12px 40px rgba(0,0,0,.2); width:260px; z-index:999; animation:popUp .2s ease; }
@keyframes popUp { from{opacity:0;transform:translateX(-50%) translateY(8px)} to{opacity:1;transform:translateX(-50%) translateY(0)} }
.se-add-page-popup p { margin:0 0 10px; font-size:13px; font-weight:600; color:#333; }
.se-add-page-popup input { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ddd; font-size:13px; margin-bottom:12px; outline:none; }
.se-add-page-popup input:focus { border-color:#6366f1; }
.se-proceed-btn { width:100%; padding:12px; border-radius:8px; background:#333; color:#fff; font-size:13px; font-weight:600; border:none; cursor:pointer; }
.se-proceed-btn:disabled { opacity:.5; }

/* Pages Row */
.se-pages-row { display:flex; gap:16px; justify-content:center; padding:0; position:relative; }
.se-page-col { display:flex; flex-direction:column; align-items:center; position:relative; width:240px; }
.se-page-col::before { content:''; position:absolute; top:0; left:0; right:0; height:1px; background:#d0d0d0; }
.se-page-col:first-child::before { left:50%; }
.se-page-col:last-child::before { right:50%; }
.se-page-col:first-child:last-child::before { display:none; }
.se-tree-vline-sm { width:1px; height:20px; background:#d0d0d0; flex-shrink:0; }
.se-page-col:not(:first-child):not(:last-child)::before { left:-8px; right:-8px; }

/* Dropdown */
.se-dropdown { position:absolute; top:32px; right:0; background:#fff; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,.12); padding:6px 0; z-index:30; min-width:130px; animation:fadeIn .15s ease; }
.se-dropdown button { display:flex; align-items:center; gap:8px; width:100%; padding:10px 16px; border:none; background:none; font-size:13px; color:#333; cursor:pointer; }
.se-dropdown button:hover { background:#f5f5f5; }

/* Rename Input */
.se-rename-input { border:1px solid #6366f1; border-radius:4px; padding:2px 6px; font-size:14px; font-weight:700; width:120px; outline:none; }

/* Sidebar - floating card */
.se-sidebar { position:fixed; top:80px; right:24px; width:337px; background:#fff; border:1px solid #e0e0e0; border-radius:16px; z-index:90; overflow-y:auto; max-height:calc(100vh - 120px); box-shadow:0 8px 40px rgba(0,0,0,.1); animation:sidebarIn .3s cubic-bezier(.16,1,.3,1); padding:24px; }
@keyframes sidebarIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:translateX(0)} }
.se-sidebar-header { display:flex; justify-content:space-between; align-items:center; }
.se-sidebar-header h3 { font-size:16px; font-weight:700; color:#111; margin:0; }
.se-sidebar-edit hr { border:none; border-top:1px solid #eee; margin:20px 0; }
.se-sidebar-edit label { display:block; font-size:13.5px; font-weight:600; color:#222; margin-bottom:10px; }
.se-sidebar-edit input { width:100%; padding:7px 14px; border-radius:15px; border:1px solid #ddd; font-size:14px; margin-bottom:24px; outline:none; color:#111; }
.se-sidebar-edit input:focus { border-color:#6366f1; }
.se-prompt-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
.se-enhance-btn { display:flex; align-items:center; gap:5px; background:none; border:none; color:#4f46e5; font-size:13px; font-weight:600; cursor:pointer; }
.se-enhance-btn:hover { color:#3b38f1; }
.se-green-dot { width:9px; height:9px; border-radius:50%; background:#22c55e; }
.se-sidebar-edit textarea { width:100%; padding:6px 9px; border-radius:18px; border:1px solid #ddd; font-size:13.5px; resize:none; outline:none; line-height:1.6; color:#333; }
.se-sidebar-edit textarea:focus { border-color:#6366f1; }
.se-trash-btn { border:none; background:none; cursor:pointer; padding:5px; border-radius:6px; transition:background .15s; }
.se-trash-btn:hover { background:#fee2e2; }

/* Add Sections Sidebar */
.se-sidebar-add h3 { font-size:16px; font-weight:700; color:#222; margin:0 0 16px; }
.se-section-types { display:flex; flex-direction:column; gap:4px; }
.se-type-item { display:flex; align-items:center; justify-content:space-between; padding:12px 14px; border-radius:10px; border:1px solid #eee; background:#fff; cursor:pointer; transition:all .15s; }
.se-type-item:hover { background:#f8f8ff; border-color:#d4d4f8; }
.se-type-info { display:flex; flex-direction:column; gap:2px; }
.se-type-info strong { font-size:13px; color:#222; }
.se-type-info span { font-size:11px; color:#999; }
.se-type-add { font-size:18px; color:#6366f1; font-weight:700; }

/* Skeleton */
.se-skeleton-sections { padding:12px 18px; display:flex; flex-direction:column; gap:10px; }
.se-skel-bar { height:16px; border-radius:6px; background:linear-gradient(90deg,#e8e8e8 25%,#ddd 50%,#e8e8e8 75%); background-size:200% 100%; animation:shimmer 1.5s infinite; }
.se-skel-bar:nth-child(1) { width:80%; }
.se-skel-bar:nth-child(2) { width:60%; }
.se-skel-bar:nth-child(3) { width:70%; }
.se-skel-bar:nth-child(4) { width:50%; }

/* Zoom */
.se-zoom { position:fixed; bottom:36px; right:36px; background:#fff; border-radius:8px; border:1px solid #ddd; display:flex; align-items:center; gap:2px; padding:4px; z-index:80; box-shadow:0 2px 8px rgba(0,0,0,.06); }
.se-zoom button { width:32px; height:32px; border:none; background:none; cursor:pointer; font-size:16px; color:#666; border-radius:4px; }
.se-zoom button:hover { background:#f0f0f0; }
.se-zoom span { font-size:12px; color:#666; min-width:40px; text-align:center; }

/* Hint */
.se-hint { position:fixed; bottom:36px; left:36px; background:#fff; border-radius:8px; border:1px solid #ddd; padding:8px 14px; font-size:12px; color:#999; display:flex; align-items:center; gap:6px; z-index:80; }

/* Card enter animation */
.card-enter { animation: cardEnter 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; }
.sec-enter { animation: secEnter 0.3s ease both; }
@keyframes secEnter { 0%{opacity:0;transform:translateY(6px)} 100%{opacity:1;transform:translateY(0)} }
@keyframes cardEnter { 0%{opacity:0;transform:translateY(24px) scale(0.96)} 100%{opacity:1;transform:translateY(0) scale(1)} }

/* Animations */
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes slideLeft { from{transform:translateX(100%)} to{transform:translateX(0)} }
@keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
`;
