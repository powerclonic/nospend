// Utilities
import { defineStore } from "pinia";
import type { MessageType } from "@/types";

export const useAppStore = defineStore("app", {
  state: () => ({
    message: "",
    type: "" as MessageType,
    _timeout: 0,
  }),
  actions: {
    setMessage(message: string, type: MessageType) {
      clearTimeout(this._timeout);

      this.message = message;
      this.type = type;

      this._timeout = setTimeout(() => (this.message = ""), 5000);
    },
  },
});
