import { useState } from 'react';

interface Props {
  previewUrl: string;
  iframeKey: number;
  onRefresh: () => void;
}

type DeviceMode = 'desktop' | 'tablet' | 'mobile';

const devices: Record<DeviceMode, { width: string; maxWidth: string; label: string; frameClass: string }> = {
  desktop: { width: '100%', maxWidth: '100%', label: 'Desktop', frameClass: '' },
  tablet: { width: '768px', maxWidth: '768px', label: 'iPad', frameClass: 'rounded-2xl ring-4 ring-[#1a1d27]' },
  mobile: { width: '390px', maxWidth: '390px', label: 'iPhone', frameClass: 'rounded-[2.5rem] ring-4 ring-[#1a1d27]' },
};

export default function PreviewPanel({ previewUrl, iframeKey, onRefresh }: Props) {
  const [device, setDevice] = useState<DeviceMode>('desktop');

  return (
    <div className="flex flex-col h-full bg-[#0d1017]">
      {/* Webview toolbar */}
      <div className="h-10 flex items-center gap-2 px-3 bg-[#0d1017] border-b border-[#1a1d27] flex-shrink-0">
        {/* Navigation buttons */}
        <div className="flex items-center gap-0.5">
          <button className="p-1.5 text-gray-600 hover:text-gray-300 rounded-lg hover:bg-white/5 transition">
            <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button className="p-1.5 text-gray-600 hover:text-gray-300 rounded-lg hover:bg-white/5 transition">
            <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <button onClick={onRefresh} className="p-1.5 text-gray-600 hover:text-gray-300 rounded-lg hover:bg-white/5 transition">
            <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </button>
        </div>

        {/* URL bar */}
        <div className="flex-1 flex items-center bg-[#12121a] rounded-lg border border-[#1e1e2e] px-3 py-1.5 mx-2">
          <svg className="w-3 h-3 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fillRule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clipRule="evenodd" />
          </svg>
          <span className="text-xs text-gray-400 truncate">localhost:8000/preview</span>
        </div>

        {/* Device toggles */}
        <div className="flex items-center bg-[#12121a] rounded-lg border border-[#1e1e2e] p-0.5">
          {(['desktop', 'tablet', 'mobile'] as DeviceMode[]).map((d) => (
            <button
              key={d}
              onClick={() => setDevice(d)}
              className={`p-1.5 rounded-md transition-all ${
                device === d ? 'bg-blue-600/20 text-blue-400' : 'text-gray-600 hover:text-gray-300'
              }`}
              title={devices[d].label}
            >
              {d === 'desktop' && (
                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z" />
                </svg>
              )}
              {d === 'tablet' && (
                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 002.25-2.25v-15a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 4.5v15a2.25 2.25 0 002.25 2.25z" />
                </svg>
              )}
              {d === 'mobile' && (
                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
              )}
            </button>
          ))}
        </div>

        {/* Open external */}
        <a
          href={previewUrl}
          target="_blank"
          rel="noopener noreferrer"
          className="p-1.5 text-gray-600 hover:text-gray-300 rounded-lg hover:bg-white/5 transition"
          title="Open in new tab"
        >
          <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
          </svg>
        </a>
      </div>

      {/* Preview iframe */}
      <div className={`flex-1 overflow-auto ${device !== 'desktop' ? 'flex items-start justify-center p-6 bg-[#080810]' : ''}`}>
        <div
          className={`bg-white transition-all duration-300 ${devices[device].frameClass} overflow-hidden`}
          style={{
            width: devices[device].width,
            maxWidth: devices[device].maxWidth,
            height: device === 'desktop' ? '100%' : 'calc(100vh - 200px)',
          }}
        >
          {previewUrl ? (
            <iframe
              key={iframeKey}
              src={previewUrl}
              className="w-full h-full border-0"
              title="Preview"
              sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
            />
          ) : (
            <div className="w-full h-full flex items-center justify-center bg-[#0d1017]">
              <div className="text-center">
                <div className="w-12 h-12 mx-auto mb-3 rounded-xl bg-[#12121a] border border-[#1e1e2e] flex items-center justify-center">
                  <svg className="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </div>
                <p className="text-xs text-gray-600">No preview available</p>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}