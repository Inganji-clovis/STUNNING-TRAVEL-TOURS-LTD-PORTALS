import { prisma } from "@/lib/prisma";
import { 
  Users, 
  UserCheck, 
  UserMinus, 
  Activity,
  ArrowUpRight,
  TrendingUp,
  CreditCard,
  FileText
} from "lucide-react";

export default async function AdminDashboard() {
  const [userCount, activeUsers, roleCounts] = await Promise.all([
    prisma.user.count(),
    prisma.user.count({ where: { is_active: true } }),
    prisma.user.groupBy({
      by: ['role'],
      _count: true,
    }),
  ]);

  const stats = [
    { label: "Total Personnel", value: userCount, icon: Users, color: "text-blue-600", bg: "bg-blue-50" },
    { label: "Active Sessions", value: activeUsers, icon: UserCheck, color: "text-emerald-600", bg: "bg-emerald-50" },
    { label: "Reports Generated", value: "24", icon: FileText, color: "text-amber-600", bg: "bg-amber-50" },
    { label: "Security Alerts", value: "0", icon: Activity, color: "text-red-600", bg: "bg-red-50" },
  ];

  return (
    <div className="space-y-10">
      {/* Welcome Section */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase">Admin Console</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1">Stunning Travel & Tours Ltd &bull; HQ</p>
        </div>
        <div className="flex items-center gap-2 bg-white p-1 rounded-2xl border border-slate-200 shadow-sm">
          <button className="px-6 py-2.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all">
            System Report
          </button>
          <button className="px-6 py-2.5 bg-white text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all border border-slate-100">
            Refresh
          </button>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, i) => {
          const Icon = stat.icon;
          return (
            <div key={i} className="bg-white p-8 rounded-[2rem] border border-red-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group relative overflow-hidden">
               <div className={`absolute top-0 right-0 w-32 h-32 ${stat.bg} rounded-full -mr-16 -mt-16 opacity-40 group-hover:scale-110 transition-transform`} />
               
               <div className="relative z-10">
                 <div className={`w-12 h-12 ${stat.bg} ${stat.color} rounded-xl flex items-center justify-center mb-6`}>
                   <Icon size={24} />
                 </div>
                 <p className="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] mb-1">{stat.label}</p>
                 <div className="flex items-baseline gap-2">
                   <h3 className="text-3xl font-black text-slate-900">{stat.value}</h3>
                   <span className="text-emerald-500 text-[10px] font-bold flex items-center gap-0.5">
                     <TrendingUp size={10} /> +2.4%
                   </span>
                 </div>
               </div>
            </div>
          );
        })}
      </div>

      {/* Role Distribution & Activity logs */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2 bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm">
           <div className="flex justify-between items-center mb-10">
              <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight">Personnel Distribution</h2>
              <button className="text-[10px] font-black text-red-600 uppercase tracking-widest hover:underline">View All Users</button>
           </div>
           
           <div className="space-y-6">
              {roleCounts.map((role, i) => (
                <div key={i} className="space-y-2">
                   <div className="flex justify-between text-[11px] font-black uppercase tracking-wider">
                      <span className="text-slate-500">{role.role}</span>
                      <span className="text-slate-900">{role._count} Officers</span>
                   </div>
                   <div className="h-3 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100">
                      <div 
                        className="h-full bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.3)]" 
                        style={{ width: `${(role._count / userCount) * 100}%` }}
                      />
                   </div>
                </div>
              ))}
           </div>
        </div>

        <div className="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden">
           <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl" />
           
           <h2 className="text-xl font-black uppercase tracking-tight mb-8 relative z-10">System Gateway</h2>
           <div className="space-y-6 relative z-10">
              <div className="p-5 bg-white/10 rounded-2xl border border-white/5 backdrop-blur-md">
                 <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Turso Sync Status</p>
                 <div className="flex items-center gap-3">
                    <div className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
                    <span className="text-sm font-bold uppercase tracking-widest">Active & Operational</span>
                 </div>
              </div>

              <div className="p-5 bg-white/5 rounded-2xl border border-white/5 border-dashed">
                 <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-3">Quick Actions</p>
                 <div className="grid grid-cols-2 gap-2">
                    <button className="p-3 bg-red-600 hover:bg-red-700 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Flush Logs</button>
                    <button className="p-3 bg-white/10 hover:bg-white/20 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">New User</button>
                 </div>
              </div>
           </div>

           <div className="mt-12">
              <p className="text-slate-500 text-[10px] font-bold uppercase tracking-widest text-center">
                Portal Version 2.0.1 (NextGen)
              </p>
           </div>
        </div>
      </div>
    </div>
  );
}
