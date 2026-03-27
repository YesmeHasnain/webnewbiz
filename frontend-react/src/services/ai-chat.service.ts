import api from './api';

export interface ChatMessage {
  role: 'user' | 'assistant';
  content: string;
}

export interface AiSuggestion {
  icon: string;
  text: string;
  prompt: string;
}

export const aiChatService = {
  sendMessage(websiteId: number, message: string, history: ChatMessage[]) {
    return api.post<{ success: boolean; reply: string }>(`/websites/${websiteId}/ai-chat`, {
      message,
      history,
    });
  },

  getSuggestions(websiteId: number) {
    return api.get<{ success: boolean; suggestions: AiSuggestion[] }>(`/websites/${websiteId}/ai-chat/suggestions`);
  },
};