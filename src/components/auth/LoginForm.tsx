"use client";

import { useFormState, useFormStatus } from "react-dom";
import { authenticate } from "@/lib/auth-actions";

export default function LoginForm() {
  const [errorMessage, dispatch] = useFormState(authenticate, undefined);

  return (
    <form action={dispatch} className="space-y-6">
      <div className="space-y-2">
        <label className="text-sm font-black text-slate-900 uppercase tracking-widest ml-1">
          Username
        </label>
        <div className="relative group">
          <input
            type="text"
            name="username"
            required
            placeholder="Personnel Username"
            className="w-full bg-slate-50/50 border border-slate-700/30 rounded-xl px-5 py-3.5 focus:bg-white focus:border-red-600 focus:ring-4 focus:ring-red-600/10 outline-none transition-all font-bold text-slate-900 placeholder:text-slate-400 text-sm"
          />
          <span className="absolute right-5 top-3.5 text-slate-400 group-focus-within:text-red-600 transition-colors">
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </span>
        </div>
      </div>

      <div className="space-y-2">
        <div className="flex justify-between items-center px-1">
          <label className="text-sm font-black text-slate-900 uppercase tracking-widest">
            Access Key
          </label>
          <a href="#" className="text-sm font-black text-red-600 hover:text-red-900 uppercase tracking-widest transition-colors">
            Restore Access?
          </a>
        </div>
        <div className="relative group">
          <input
            type="password"
            name="password"
            required
            placeholder="••••••••"
            className="w-full bg-slate-50/50 border border-slate-700/30 rounded-xl px-5 py-3.5 focus:bg-white focus:border-red-600 focus:ring-4 focus:ring-red-600/10 outline-none transition-all font-bold text-slate-900 placeholder:text-slate-400 text-sm"
          />
          <span className="absolute right-5 top-3.5 text-slate-400 group-focus-within:text-red-600 transition-colors">
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </span>
        </div>
      </div>

      {errorMessage && (
        <div className="p-4 bg-red-50/50 backdrop-blur-md border border-red-100 rounded-xl flex items-center space-x-3 text-red-600 animate-bounce">
          <svg className="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <p className="text-sm font-black uppercase tracking-tight leading-normal">
            {errorMessage}
          </p>
        </div>
      )}

      <LoginButton />
    </form>
  );
}

function LoginButton() {
  const { pending } = useFormStatus();

  return (
    <button
      type="submit"
      disabled={pending}
      className="w-full bg-red-600 hover:bg-red-800 text-white py-4 rounded-xl font-black uppercase tracking-[0.2em] text-sm shadow-xl shadow-red-900/20 transition-all hover:-translate-y-0.5 active:scale-[0.98] duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
    >
      {pending ? "Authenticating..." : "Login"}
    </button>
  );
}
