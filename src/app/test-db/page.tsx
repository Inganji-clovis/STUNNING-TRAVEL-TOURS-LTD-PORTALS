import { prisma } from "@/lib/prisma";

export default async function TestPage() {
  let userCount = 0;
  let error = null;

  try {
    userCount = await prisma.user.count();
  } catch (e: any) {
    error = e.message;
  }

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-black text-white p-10 font-[sans-serif]">
      <div className="max-w-md w-full bg-zinc-900 border border-zinc-800 rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
        <h1 className="text-3xl font-bold bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent mb-6">
          Database Connection Test
        </h1>
        
        {error ? (
          <div className="p-4 bg-red-900/30 border border-red-500/50 rounded-2xl text-red-200">
            <p className="font-semibold mb-1">Error connecting to Turso:</p>
            <code className="text-xs break-all">{error}</code>
          </div>
        ) : (
          <div className="space-y-4">
            <div className="p-4 bg-emerald-900/30 border border-emerald-500/50 rounded-2xl">
              <p className="text-emerald-200 font-medium">✨ Successfully connected to Turso!</p>
            </div>
            <div className="flex items-center justify-between p-4 bg-zinc-800/50 rounded-2xl border border-zinc-700">
              <span className="text-zinc-400">Total Users:</span>
              <span className="text-2xl font-mono font-bold text-white">{userCount}</span>
            </div>
          </div>
        )}

        <div className="mt-8 pt-6 border-t border-zinc-800 flex flex-col gap-2">
           <p className="text-zinc-500 text-xs text-center">
             STUNNING TRAVEL & TOURS LTD • PORTAL V2
           </p>
        </div>
      </div>
    </div>
  );
}
