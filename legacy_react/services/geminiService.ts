import { GoogleGenAI } from "@google/genai";

const getAiClient = () => {
  const apiKey = process.env.API_KEY || ''; 
  return new GoogleGenAI({ apiKey });
};

export const generateTemplate = async (topic: string, channel: string, tone: string) => {
  try {
    const ai = getAiClient();
    // Updated context for Cricket/Sports
    const prompt = `You are a helpful assistant for a Cricket Sports Facility called "PlayCricket".
    Write a professional ${channel} message template about "${topic}".
    The tone should be ${tone}.
    Keep it concise.
    Context: Cricket match bookings, tournament invites, membership renewals, or rain delays.
    If it is for WhatsApp, include placeholders like {{1}}, {{2}} for variables.
    If it is for SMS, keep it under 160 characters.`;

    const response = await ai.models.generateContent({
      model: 'gemini-2.5-flash',
      contents: prompt,
    });

    return response.text;
  } catch (error) {
    console.error("AI Generation Error:", error);
    return "Error generating content. Please check your API key.";
  }
};

export const analyzeSentiment = async (messages: string[]) => {
  try {
    const ai = getAiClient();
    const prompt = `Analyze the sentiment of the following member messages regarding their cricket bookings/experience. Return a JSON object with properties: 'score' (number -1 to 1), 'summary' (string), and 'suggestedAction' (string).
    
    Messages:
    ${messages.join('\n')}
    `;

    const response = await ai.models.generateContent({
        model: 'gemini-2.5-flash',
        contents: prompt,
        config: { responseMimeType: 'application/json' }
    });
    
    return JSON.parse(response.text);
  } catch (error) {
    console.error("Sentiment Analysis Error:", error);
    return null;
  }
};
