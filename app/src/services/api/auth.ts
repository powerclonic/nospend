import client from "@/services/api";
import type { SignInData, SignUpData } from "@/types";

export default {
  signin: (data: SignInData) => {
    return client.post("/login", data);
  },

  signup: (data: SignUpData) => {
    return client.post("/register", data);
  },

  logout: () => {
    return client.post("/logout");
  },
};
