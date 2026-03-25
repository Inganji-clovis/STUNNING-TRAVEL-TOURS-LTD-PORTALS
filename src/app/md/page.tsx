import { prisma } from "@/lib/prisma";
import { 
  Users, 
  Activity, 
  ShieldCheck, 
  History, 
  ArrowUpRight, 
  TrendingUp,
  Briefcase,
  UserCheck,
  DollarSign,
  Lock,
  ArrowRight
} from "lucide-react";
import Link from "next/link";

export default async function MdDashboard() {
  const [employees, activityLogs, cashStats, ticketStats] = await Promise.all([
    prisma.user.findMany({
      where: { role: { in: ['sales', 'operations', 'admin', 'md'] } },
      orderBy: { created_at: 'desc' },
      take: 5
    }),
    prisma.activityLog.findMany({
      orderBy: { created_at: 'desc' },
      take: 10,
      include: { user: true }
    }),
    prisma.cashDay.aggregate({
      _sum: { closing_balance: true, closing_balance_usd: true }
    }),
    prisma.cashTransaction.aggregate({
      _sum: { service_fee: true }
    })
  ]);

  const totalRevenueRWF = Number(cashStats._sum.closing_balance || 0);
  const totalProfitRWF = Number(ticketStats._sum.service_fee || 0);
  const totalStaff = await prisma.user.count({ where: { is_active: true } });

  const stats = [
    { label: "Global Liquidity", value: "RWF " + totalRevenueRWF.toLocaleString(), icon: TrendingUp, color: "text-emerald-600", bg: "bg-emerald-50" },
    { label: "Aggregate Profit", value: "RWF " + totalProfitRWF.toLocaleString(), icon: DollarSign, color: "text-blue-600", bg: "bg-blue-50" },
    { label: "Staff Strength", value: totalStaff, icon: Users, color: "text-slate-900", bg: "bg-slate-50" },
    { label: "Active Nodes", value: "HQ ONLINE", icon: ShieldCheck, color: "text-emerald-600", bg: "bg-emerald-50" },
  ];

  return (
    <div className="space-y-10 pb-20">
      {/* Executive Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic underline decoration-slate-200 decoration-8 underline-offset-8">MD Command Center</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-3 italic">Intelligence, Oversight & HQ Strategy</p>
        </div>
        <div className="flex items-center gap-4">
           <div className="px-6 py-3 bg-white border border-slate-200 rounded-2xl flex items-center gap-3 italic">
              <div className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
              <span className="text-[10px] font-black text-slate-900 uppercase tracking-widest leading-none">Global Sync Status: SECURE</span>
           </div>
        </div>
      </div>

      {/* Grid Stats */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, i) => {
          const Icon = stat.icon;
          return (
            <div key={i} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group relative overflow-hidden italic">
               <div className={`absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity`}>
                  <Icon size={80} />
               </div>
               
               <div className="relative z-10">
                 <div className={`w-12 h-12 ${stat.bg} ${stat.color} rounded-2xl flex items-center justify-center mb-6 shadow-sm`}>
                    <Icon size={24} />
                 </div>
                 <p className="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] mb-2">{stat.label}</p>
                 <h3 className="text-2xl font-black text-slate-900 tracking-tight">{stat.value}</h3>
               </div>
            </div>
          );
        })}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Global Activity Log */}
        <div className="lg:col-span-2 bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm italic">
           <div className="flex justify-between items-center mb-10">
              <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-4">
                 <History size={26} className="text-blue-600" />
                 Global Intelligence Stream
              </h2>
              <Link href="/md/activity" className="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Full Audit Trail</Link>
           </div>
           
           <div className="space-y-8">
              {activityLogs.length > 0 ? activityLogs.map((log) => (
                <div key={log.id} className="flex gap-6 items-start group">
                   <div className="w-12 h-12 shrink-0 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-900 font-black text-xs uppercase shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all">
                      {log.user.name[0]}
                   </div>
                   <div className="flex-1 pt-1 border-b border-slate-50 pb-6 group-last:border-0 group-last:pb-0">
                      <div className="flex justify-between items-baseline mb-2">
                         <span className="text-sm font-black text-slate-900 uppercase tracking-tight">{log.user.name}</span>
                         <span className="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{new Date(log.created_at).toLocaleString([], { hour: '2-digit', minute: '2-digit' })} • {new Date(log.created_at).toLocaleDateString()}</span>
                      </div>
                      <p className="text-xs text-slate-500 font-bold leading-relaxed">
                         <span className={`px-2 py-0.5 rounded uppercase text-[8px] font-black mr-3 tracking-widest ${
                           log.portal === 'Admin' ? 'bg-red-50 text-red-600' :
                           log.portal === 'Sales' ? 'bg-blue-50 text-blue-600' :
                           'bg-emerald-50 text-emerald-600'
                         }`}>{log.portal}</span>
                         <span className="text-slate-400 font-black mr-2">[{log.action}]</span>
                         {log.description}
                      </p>
                   </div>
                </div>
              )) : (
                <div className="py-20 text-center opacity-30 italic font-black uppercase tracking-[0.2em]">Zero Activity Logged</div>
              )}
           </div>
        </div>

        {/* Staff Base Sidebar */}
        <div className="space-y-6 italic">
           <div className="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden group shadow-xl">
              <div className="absolute top-0 right-0 p-4 opacity-10">
                 <Users size={100} />
              </div>
              <h2 className="text-xl font-black uppercase tracking-tight mb-8 relative z-10">Staff Intelligence</h2>
              <div className="space-y-6 relative z-10">
                 {employees.map((emp) => (
                   <div key={emp.id} className="flex items-center justify-between group/item">
                      <div className="flex items-center gap-4">
                         <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-[10px] font-black group-hover/item:bg-white group-hover/item:text-slate-900 transition-all shadow-sm">
                            {emp.name[0]}
                         </div>
                         <div>
                            <p className="text-xs font-black uppercase tracking-widest leading-none">{emp.name}</p>
                            <p className="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-2">{emp.role}</p>
                         </div>
                      </div>
                      <div className="flex items-center gap-2">
                         <div className="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] animate-pulse" />
                      </div>
                   </div>
                 ))}
                 <Link href="/md/employees" className="w-full py-4 mt-4 bg-white/5 hover:bg-white/10 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all border border-white/5 flex items-center justify-center gap-2">
                    Personnel Directory <ArrowRight size={14} />
                 </Link>
              </div>
           </div>

           <div className="p-10 bg-blue-600 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden">
              <div className="absolute top-0 right-0 p-8 opacity-10">
                 <Lock size={120} />
              </div>
              <div className="relative z-10">
                 <div className="flex items-center gap-4 mb-6">
                    <div className="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center shadow-lg border border-white/10">
                       <Briefcase size={24} />
                    </div>
                    <h2 className="text-lg font-black uppercase tracking-tight">Executive Vault</h2>
                 </div>
                 <p className="text-[10px] font-bold text-blue-100 uppercase leading-relaxed tracking-widest mb-10 opacity-80">
                    Proprietary HQ documents and corporate sensitive dispatches are stored here.
                 </p>
                 <button className="w-full py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] shadow-2xl hover:scale-105 transition-all">
                    Initiate Vault Protocol
                 </button>
              </div>
           </div>
        </div>
      </div>
    </div>
  );
}
