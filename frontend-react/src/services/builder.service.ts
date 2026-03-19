import api from './api';
import type { AnalysisResult, QuestionAnalysis, BuildSummary, LayoutOption } from '../models/types';

export const builderService = {
  analyzePrompt(prompt: string) {
    return api.post<AnalysisResult>('/builder/analyze', { prompt });
  },

  analyzeWithQuestions(prompt: string) {
    return api.post<QuestionAnalysis>('/builder/analyze-questions', { prompt });
  },

  summarize(data: { prompt: string; business_name: string; business_type: string; answers: Record<string, boolean | string> }) {
    return api.post<BuildSummary>('/builder/summarize', data);
  },

  enhancePrompt(prompt: string) {
    return api.post<{ success: boolean; enhanced: string }>('/builder/enhance-prompt', { prompt });
  },

  getLayouts() {
    return api.get<LayoutOption[]>('/builder/layouts');
  },
};
