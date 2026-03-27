import api from './api';

export interface CopilotMessage {
  role: 'user' | 'assistant';
  content: string;
}

export interface CopilotAction {
  tool: string;
  input: Record<string, any>;
  result: {
    success: boolean;
    action?: string;
    error?: string;
    [key: string]: any;
  };
}

export interface CopilotResponse {
  success: boolean;
  reply: string;
  actions: CopilotAction[];
  session_id: number;
  has_changes: boolean;
}

export interface CopilotSuggestion {
  icon: string;
  text: string;
  prompt: string;
}

export interface CopilotSession {
  id: number;
  website_id: number;
  actions_count: number;
  created_at: string;
  updated_at: string;
}

const aiCopilotService = {
  /**
   * Send a message to the AI Copilot.
   */
  async chat(
    websiteId: number,
    message: string,
    history: CopilotMessage[] = [],
    pageId?: number,
    sessionId?: number
  ): Promise<CopilotResponse> {
    const { data } = await api.post(`/websites/${websiteId}/copilot/chat`, {
      message,
      history,
      page_id: pageId || null,
      session_id: sessionId || null,
    });
    return data;
  },

  /**
   * Get context-aware suggestions.
   */
  async getSuggestions(websiteId: number, pageId?: number): Promise<CopilotSuggestion[]> {
    const params = pageId ? `?page_id=${pageId}` : '';
    const { data } = await api.get(`/websites/${websiteId}/copilot/suggestions${params}`);
    return data.suggestions || [];
  },

  /**
   * Undo a copilot action.
   */
  async undo(websiteId: number, actionId: number): Promise<{ success: boolean; message?: string; error?: string }> {
    const { data } = await api.post(`/websites/${websiteId}/copilot/undo/${actionId}`);
    return data;
  },

  /**
   * List copilot sessions.
   */
  async getSessions(websiteId: number): Promise<CopilotSession[]> {
    const { data } = await api.get(`/websites/${websiteId}/copilot/sessions`);
    return data.data || [];
  },

  /**
   * Get a session with its actions.
   */
  async getSession(websiteId: number, sessionId: number): Promise<any> {
    const { data } = await api.get(`/websites/${websiteId}/copilot/session/${sessionId}`);
    return data.data;
  },
};

export default aiCopilotService;
