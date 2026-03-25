import { prisma } from "@/lib/prisma";
import { 
  Users, 
  Search, 
  Filter, 
  MoreVertical, 
  ShieldCheck, 
  Mail, 
  Phone,
  UserCheck,
  Briefcase,
  Globe,
  Plus
} from "lucide-react";
import Link from "next/link";

export default async function EmployeeBasePage({
  searchParams,
}: {
  searchParams: Promise<{ role?: string; search?: string }>;
}) {
  const { role = "", search = "" } = await searchParams;

  const employees = await prisma.user.findMany({
    where: {
      ...(role && { role }),
      OR: [
        { name: { contains: search } },
        { email: { contains: search } },
        { phone: { contains: search } }
      ]
    },
    orderBy: { created_at: 'desc' },
  });

  const roles = ["admin", "md", "sales", "operations"];

  return (
    <div className="space-y-8 pb-20 italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic underline decoration-slate-200 decoration-8 underline-offset-8">Personnel Directory</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-3 italic">Stunning Travel Global Staff Intelligence</p>
        </div>
        <button className="px-6 py-3 bg-slate-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] flex items-center gap-2 hover:scale-[1.02] transition-all shadow-lg">
           <Plus size={14} /> Provision New Staff
        </button>
      </div>

      {/* Filter Bar */}
      <div className="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex flex-wrap items-center gap-6">
         <div className="flex-1 min-w-[300px] relative">
            <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
            <input 
              type="text" 
              placeholder="Search personnel by name, email, or digital ID..."
              className="w-full pl-12 pr-6 py-3 bg-slate-50 border border-slate-100 rounded-xl font-black text-xs focus:outline-none focus:border-slate-900 transition-all uppercase italic placeholder:text-slate-300"
            />
         </div>
         
         <div className="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0">
            <Link href="/md/employees" className={`px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all ${!role ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-slate-50 text-slate-400 border-slate-100 hover:text-slate-900'}`}>
               All Personnel
            </Link>
            {roles.map(r => (
              <Link key={r} href={`/md/employees?role=${r}`} className={`px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all ${role === r ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-slate-50 text-slate-400 border-slate-100 hover:text-slate-900'}`}>
                 {r}
              </Link>
            ))}
         </div>
      </div>

      {/* Grid View */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        {employees.length > 0 ? employees.map((emp) => (
          <div key={emp.id} className="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm hover:shadow-2xl transition-all group relative overflow-hidden flex flex-col justify-between min-h-[400px]">
             {/* Subtle pattern background for role */}
             <div className="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                <Globe size={150} />
             </div>

             <div className="relative z-10">
                <div className="flex justify-between items-start mb-8">
                   <div className="w-16 h-16 rounded-[1.5rem] bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-900 font-black text-xl shadow-lg ring-4 ring-white group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                      {emp.name[0]}
                   </div>
                   <div className={`px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border shadow-sm italic flex items-center gap-2 ${
                     emp.role === 'admin' ? 'bg-red-50 text-red-600 border-red-100' :
                     emp.role === 'md' ? 'bg-slate-900 text-white border-slate-900' :
                     emp.role === 'sales' ? 'bg-blue-50 text-blue-600 border-blue-100' :
                     'bg-emerald-50 text-emerald-600 border-emerald-100'
                   }`}>
                     <ShieldCheck size={12} />
                     {emp.role}
                   </div>
                </div>

                <div className="space-y-6">
                   <div className="space-y-1">
                      <h3 className="text-2xl font-black text-slate-900 tracking-tight uppercase italic leading-none">{emp.name}</h3>
                      <p className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] pt-1">
                         STN UNIT: {emp.role.toUpperCase()} • ID: #STN-2026-{emp.id.toString().padStart(3, '0')}
                      </p>
                   </div>

                   <div className="space-y-4 pt-4">
                      <div className="flex items-center gap-4 text-slate-500 bg-slate-50/50 p-3 rounded-2xl border border-slate-50 group-hover:bg-white transition-colors">
                         <div className="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-blue-500 shadow-sm">
                            <Mail size={14} />
                         </div>
                         <span className="text-xs font-black truncate tracking-tight lowercase">{emp.email}</span>
                      </div>
                      <div className="flex items-center gap-4 text-slate-500 bg-slate-50/50 p-3 rounded-2xl border border-slate-50 group-hover:bg-white transition-colors">
                         <div className="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-emerald-500 shadow-sm">
                            <Phone size={14} />
                         </div>
                         <span className="text-xs font-black tracking-tight">{emp.phone || 'DATA MISSING'}</span>
                      </div>
                   </div>
                </div>
             </div>

             <div className="relative z-10 pt-8 mt-4 border-t border-slate-50 flex items-center justify-between">
                <div className="flex items-center gap-3">
                   <div className={`w-3 h-3 rounded-full ${emp.is_active ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.5)] animate-pulse' : 'bg-slate-300'}`} />
                   <span className="text-[10px] font-black text-slate-900 uppercase tracking-[0.1em] italic">{emp.is_active ? 'Active Node' : 'Suspended'}</span>
                </div>
                <div className="flex gap-2">
                   <button className="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:bg-white hover:shadow-md transition-all">
                      <MoreVertical size={16} />
                   </button>
                   <button className="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all">
                      Overview
                   </button>
                </div>
             </div>
          </div>
        )) : (
          <div className="col-span-full py-40 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100 flex flex-col items-center justify-center gap-6 text-center opacity-30 italic">
             <Users size={64} className="text-slate-200" />
             <p className="font-black uppercase tracking-[0.2em] text-sm">Personnel Ledger Vacant</p>
          </div>
        )}
      </div>
    </div>
  );
}
