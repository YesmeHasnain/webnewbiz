// WebNewBiz API client for Figma Plugin

const API_BASE = 'https://api.webnewbiz.app/api';

let apiKey = '';

export function setApiKey(key: string) { apiKey = key; }

async function request(endpoint: string, options: RequestInit = {}): Promise<unknown> {
  const res = await fetch(`${API_BASE}${endpoint}`, {
    ...options,
    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${apiKey}`, ...options.headers },
  });
  if (!res.ok) throw new Error(`API Error: ${res.status}`);
  return res.json();
}

export async function generateDesign(prompt: string, style: string, colorTheme?: string) {
  return request('/figma/generate-design', {
    method: 'POST',
    body: JSON.stringify({ prompt, style, color_theme: colorTheme }),
  });
}

export async function imageToDesign(imageBase64: string) {
  return request('/figma/image-to-design', {
    method: 'POST',
    body: JSON.stringify({ image: imageBase64 }),
  });
}

export async function exportToCode(figmaFileId: string, frameId: string, outputType: string) {
  return request('/figma/export-to-code', {
    method: 'POST',
    body: JSON.stringify({ figma_file_id: figmaFileId, frame_id: frameId, output_type: outputType }),
  });
}

export async function getProjects() {
  return request('/figma/projects');
}
