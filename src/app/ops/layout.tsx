import Sidebar from "@/components/layout/Sidebar";
import { auth } from "@/auth";
import { redirect } from "next/navigation";

export default async function OperationsLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const session = await auth();
  const role = (session?.user as any)?.role;
  
  if (role !== "operations" && role !== "admin") {
    redirect("/");
  }

  return (
    <div className="flex min-h-screen bg-[#F8FAFC]">
      <div 
        className="fixed inset-0 z-0 opacity-[0.03] pointer-events-none grayscale" 
        style={{ 
          backgroundImage: "url('/assets/brand/clear_plane.png')",
          backgroundSize: 'cover',
          backgroundPosition: 'center'
        }}
      />

      <Sidebar />

      <main className="flex-1 flex flex-col relative z-20 overflow-x-hidden">
        <header className="sticky top-0 z-30 w-full bg-white/80 backdrop-blur-xl border-b border-slate-200 px-8 py-4 flex items-center justify-between">
          <div className="flex items-center gap-4">
            <span className="text-emerald-700 font-black text-xs uppercase tracking-widest bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
              OPERATIONS & LOGISTICS CONSOLE
            </span>
          </div>

          <div className="flex items-center gap-4">
             <div className="text-right hidden sm:block">
               <p className="text-xs font-black text-slate-900 leading-tight uppercase tracking-tighter">{session?.user?.name}</p>
               <p className="text-[10px] font-bold text-slate-400 tracking-widest uppercase">operations officer</p>
             </div>
             <div className="w-10 h-10 rounded-xl bg-emerald-900 flex items-center justify-center text-white font-black text-xs border-2 border-slate-100 shadow-sm">
               {session?.user?.name?.[0]}
             </div>
          </div>
        </header>

        <div className="p-8 lg:p-12 max-w-7xl w-full mx-auto">
          {children}
        </div>
      </main>
    </div>
  );
}
