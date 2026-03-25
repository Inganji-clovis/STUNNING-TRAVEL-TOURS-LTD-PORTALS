import { prisma } from "@/lib/prisma";
import { 
  BarChart3, 
  TrendingUp, 
  TrendingDown, 
  PieChart, 
  Calendar,
  Download,
  Filter,
  ArrowUpRight,
  ArrowDownRight,
  DollarSign,
  Coins,
  ShieldCheck,
  Briefcase
} from "lucide-react";
import Link from "next/link";

export default async function FinancialReportsPage({
  searchParams,
}: {
  searchParams: Promise<{ period?: string }>;
}) {
  const { period = "month" } = await searchParams;

  // Aggregate Data
  const [cashStats, incomeByCategory, expenseByCategory] = await Promise.all([
    prisma.cashTransaction.aggregate({
      _sum: { amount: true, ttc: true, service_fee: true },
    }),
    prisma.cashTransaction.groupBy({
      by: ['category_id'],
      where: { type: 'income' },
      _sum: { amount: true }
    }),
    prisma.cashTransaction.groupBy({
      by: ['category_id'],
      where: { type: 'expense' },
      _sum: { amount: true }
    })
  ]);

  const categories = await prisma.cashCategory.findMany();

  return (
    <div className="space-y-10 pb-20 italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic underline decoration-slate-200 decoration-8 underline-offset-8">Financial Analytics</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-3 italic">Macro-Fiscal performance & In-Depth Reporting</p>
        </div>
        <div className="flex gap-3">
           <button className="px-6 py-3 bg-white border border-slate-200 rounded-xl font-black uppercase tracking-widest text-[10px] flex items-center gap-2 hover:scale-[1.02] transition-all shadow-sm">
             <Calendar size={14} /> Custom Range
           </button>
           <button className="px-6 py-3 bg-slate-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] flex items-center gap-2 hover:scale-[1.02] transition-all shadow-lg active:scale-95">
             <Download size={14} /> Export Fiscal PDF
           </button>
        </div>
      </div>

      {/* Analytics Matrix */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        {/* Core Stats */}
        <div className="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
           <div className="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm relative overflow-hidden group">
              <div className="absolute top-0 right-0 p-6 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                 <TrendingUp size={100} />
              </div>
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Aggregate Income</p>
              <h3 className="text-3xl font-black text-slate-900 tracking-tight">RWF {Number(cashStats._sum.amount || 0).toLocaleString()}</h3>
              <div className="mt-6 flex items-center gap-2 text-emerald-600">
                 <ArrowUpRight size={14} />
                 <span className="text-[10px] font-black uppercase tracking-widest">Growth Normalized</span>
              </div>
           </div>

           <div className="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm relative overflow-hidden group">
              <div className="absolute top-0 right-0 p-6 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                 <TrendingDown size={100} />
              </div>
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Operational Outflow</p>
              <h3 className="text-3xl font-black text-slate-900 tracking-tight">RWF 0</h3>
              <div className="mt-6 flex items-center gap-2 text-red-600">
                 <ArrowDownRight size={14} />
                 <span className="text-[10px] font-black uppercase tracking-widest">OPEX Variance: MINIMAL</span>
              </div>
           </div>

           <div className="bg-slate-900 p-10 rounded-[3rem] text-white relative overflow-hidden group shadow-2xl col-span-full">
              <div className="absolute top-0 right-0 p-6 opacity-10">
                 <ShieldCheck size={120} />
              </div>
              <div className="relative z-10 flex flex-col md:flex-row justify-between items-end gap-6">
                 <div>
                    <p className="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Service Fee Revenue (Profit)</p>
                    <h3 className="text-4xl font-black tracking-tight">RWF {Number(cashStats._sum.service_fee || 0).toLocaleString()}</h3>
                 </div>
                 <div className="text-right flex flex-col items-end">
                    <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 italic">TTC Managed Liquidity</p>
                    <p className="text-xl font-black text-slate-300">RWF {Number(cashStats._sum.ttc || 0).toLocaleString()}</p>
                 </div>
              </div>
           </div>
        </div>

        {/* Category Breakdown */}
        <div className="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col justify-between">
           <div>
              <h3 className="text-sm font-black uppercase tracking-widest mb-8 flex items-center gap-3">
                 <PieChart size={20} className="text-blue-600" />
                 Sector Breakdown
              </h3>
              <div className="space-y-6">
                 {incomeByCategory.slice(0, 5).map((cat) => {
                   const category = categories.find(c => c.id === cat.category_id);
                   const percentage = cashStats._sum.amount ? (Number(cat._sum.amount) / Number(cashStats._sum.amount) * 100).toFixed(0) : 0;
                   return (
                     <div key={cat.category_id} className="space-y-2">
                        <div className="flex justify-between items-center text-[10px] font-black uppercase tracking-widest italic">
                           <span className="text-slate-900 leading-none">{category?.name || 'Unknown'}</span>
                           <span className="text-blue-600">{percentage}%</span>
                        </div>
                        <div className="h-1.5 bg-slate-50 rounded-full overflow-hidden">
                           <div className="h-full bg-blue-600 transition-all duration-1000" style={{ width: `${percentage}%` }} />
                        </div>
                     </div>
                   );
                 })}
                 {incomeByCategory.length === 0 && (
                   <div className="py-20 text-center opacity-30 font-black uppercase tracking-widest text-[10px]">No Category Data</div>
                 )}
              </div>
           </div>
           
           <div className="mt-10 pt-8 border-t border-slate-50">
              <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
                 Liquidity distribution concentrated in core travel services and ticket ticketing sectors.
              </p>
           </div>
        </div>
      </div>

      {/* Visual Analytics Simulation */}
      <div className="bg-white rounded-[3.5rem] p-12 border border-slate-200 shadow-sm min-h-[400px] flex flex-col justify-between relative overflow-hidden">
         <div className="flex justify-between items-center mb-12 relative z-10">
            <div>
               <h3 className="text-2xl font-black text-slate-900 uppercase italic tracking-tight">Macro-Fiscal Pulse</h3>
               <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2 italic">Monthly Inflow vs Outflow Velocity</p>
            </div>
            <div className="flex gap-6">
               <div className="flex items-center gap-3">
                  <div className="w-3 h-3 bg-blue-600 rounded-full" />
                  <span className="text-[10px] font-black uppercase tracking-widest italic">Total Inflow</span>
               </div>
               <div className="flex items-center gap-3">
                  <div className="w-3 h-3 bg-red-500 rounded-full" />
                  <span className="text-[10px] font-black uppercase tracking-widest italic">Total Outflow</span>
               </div>
            </div>
         </div>

         {/* Visual Graphic Representation (SVG/CSS) */}
         <div className="flex-1 flex items-end justify-between h-64 gap-3 relative z-10 px-4">
            {Array.from({ length: 12 }).map((_, i) => (
              <div key={i} className="flex-1 group relative flex flex-col items-center justify-end h-full">
                 <div className="absolute -top-12 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest whitespace-nowrap shadow-xl">
                    DEC: RWF 1.2M
                 </div>
                 <div className="w-full max-w-[40px] bg-blue-600/10 rounded-2xl group-hover:bg-blue-600/30 transition-all relative overflow-hidden flex flex-col justify-end" style={{ height: `${Math.random() * 60 + 40}%` }}>
                    <div className="w-full bg-blue-600 rounded-2xl transition-all duration-1000 shadow-[0_0_20px_rgba(37,99,235,0.3)]" style={{ height: `${Math.random() * 80 + 20}%` }} />
                 </div>
                 <p className="text-[9px] font-black text-slate-300 mt-4 uppercase italic">M{i+1}</p>
              </div>
            ))}
         </div>

         <div className="absolute bottom-0 left-0 right-0 p-12 opacity-5 pointer-events-none">
            <BarChart3 size={400} />
         </div>
      </div>
    </div>
  );
}
