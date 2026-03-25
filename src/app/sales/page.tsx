import { prisma } from "@/lib/prisma";
import { 
  Wallet, 
  TrendingUp, 
  TrendingDown, 
  CreditCard, 
  FileText,
  Clock,
  CheckCircle2,
  AlertCircle,
  Building2,
  Lock,
  ArrowRight,
  ShieldCheck
} from "lucide-react";
import Link from "next/link";
import { closeBusinessDay } from "@/lib/cashbook-actions";

export default async function SalesDashboard() {
  // Fetch summary data
  const [cashDay, pendingInvoices, totalTransactions, taxStats] = await Promise.all([
    prisma.cashDay.findFirst({
      where: { status: 'open' },
      orderBy: { date: 'desc' },
      include: { transactions: { include: { category: true } } }
    }),
    prisma.billingDocument.findMany({
      where: { status: 'pending', type: 'invoice' },
      take: 5,
      orderBy: { created_at: 'desc' }
    }),
    prisma.cashTransaction.count(),
    prisma.taxCompliance.groupBy({
      by: ['type'],
      _sum: { declared_amount: true, receipt_amount: true },
    })
  ]);

  const dailyIncome = cashDay?.transactions
    .filter(t => t.type === 'income' && t.currency === 'RWF')
    .reduce((acc, curr) => acc + Number(curr.amount), 0) || 0;

  const dailyExpense = cashDay?.transactions
    .filter(t => t.type === 'expense' && t.currency === 'RWF')
    .reduce((acc, curr) => acc + Number(curr.amount), 0) || 0;

  const stats = [
    { label: "Main Vault (RWF)", value: (Number(cashDay?.closing_balance || 0)).toLocaleString(), icon: Wallet, color: "text-blue-600", bg: "bg-blue-50" },
    { label: "In-Day Inflow", value: dailyIncome.toLocaleString(), icon: TrendingUp, color: "text-emerald-600", bg: "bg-emerald-50" },
    { label: "In-Day Outflow", value: dailyExpense.toLocaleString(), icon: TrendingDown, color: "text-red-600", bg: "bg-red-50" },
    { label: "Pending Invoices", value: pendingInvoices.length, icon: FileText, color: "text-amber-600", bg: "bg-amber-50" },
  ];

  return (
    <div className="space-y-10 pb-20">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic underline decoration-slate-200 decoration-8 underline-offset-8">Financial Command</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-3 italic">Macro-Finance Oversight & Fiscal Compliance</p>
        </div>
        <div className="flex items-center gap-4">
           {cashDay ? (
             <div className="px-6 py-3 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 text-[10px] font-black uppercase tracking-widest flex items-center gap-3 italic">
                <div className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
                Trading Day: {new Date(cashDay.date).toLocaleDateString()}
             </div>
           ) : (
             <div className="px-6 py-3 bg-red-50 text-red-700 rounded-2xl border border-red-100 text-[10px] font-black uppercase tracking-widest flex items-center gap-3 italic">
                <Lock size={14} />
                Vaults Locked
             </div>
           )}
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, i) => {
          const Icon = stat.icon;
          return (
            <div key={i} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group relative overflow-hidden">
               <div className={`absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity`}>
                  <Icon size={80} />
               </div>
               <div className="relative z-10">
                 <div className={`w-12 h-12 ${stat.bg} ${stat.color} rounded-2xl flex items-center justify-center mb-6 shadow-sm`}>
                    <Icon size={24} />
                 </div>
                 <p className="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] mb-2 italic">{stat.label}</p>
                 <h3 className="text-2xl font-black text-slate-900 tracking-tight italic">
                    {i < 3 ? 'RWF ' : ''}{stat.value}
                 </h3>
               </div>
            </div>
          );
        })}
      </div>

      {/* Main Content */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2 space-y-8">
           {/* Pending Invoices */}
           <div className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm">
              <div className="flex justify-between items-center mb-10">
                 <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight italic flex items-center gap-3">
                    <FileText size={20} className="text-blue-600" />
                    Unsettled Accounts
                 </h2>
                 <Link href="/sales/invoices" className="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline italic">Open Ledger</Link>
              </div>
              
              <div className="overflow-x-auto whitespace-nowrap">
                 <table className="w-full text-left">
                    <thead>
                      <tr className="border-b border-slate-50">
                        <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Invoice #</th>
                        <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Fiscal Value</th>
                        <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Status</th>
                        <th className="pb-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Action</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-50">
                       {pendingInvoices.length > 0 ? pendingInvoices.map((inv) => (
                         <tr key={inv.id} className="hover:bg-slate-50/50 transition-colors group">
                            <td className="py-6">
                               <span className="font-black text-slate-900 text-xs italic uppercase">#{inv.document_number}</span>
                            </td>
                            <td className="py-6">
                               <span className="font-black text-slate-900 text-xs italic">{inv.currency} {Number(inv.amount).toLocaleString()}</span>
                            </td>
                            <td className="py-6">
                               <span className="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-amber-100 italic">Pending</span>
                            </td>
                            <td className="py-6 text-right">
                               <button className="text-blue-600 hover:text-blue-900 font-black text-[10px] uppercase tracking-widest transition-colors italic">Settle</button>
                            </td>
                         </tr>
                       )) : (
                         <tr>
                            <td colSpan={4} className="py-20 text-center opacity-30 italic font-black uppercase tracking-[0.2em] text-sm">
                               Ledger Clear
                            </td>
                         </tr>
                       )}
                    </tbody>
                 </table>
              </div>
           </div>

           {/* Tax Compliance Pulse */}
           <div className="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden italic shadow-xl">
              <div className="relative z-10">
                 <div className="flex justify-between items-center mb-10">
                    <h2 className="text-xl font-black uppercase tracking-tight flex items-center gap-3">
                       <Building2 size={24} className="text-blue-400" />
                       Fiscal Compliance Pulse
                    </h2>
                    <Link href="/sales/tax" className="text-[10px] font-black text-blue-400 uppercase tracking-widest hover:underline">Compliance Hub</Link>
                 </div>
                 
                 <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {['pension', 'maternity', 'rra'].map(type => {
                       const stats = taxStats.find(s => s.type === type);
                       const ratio = stats?._sum.receipt_amount 
                         ? ((Number(stats._sum.receipt_amount) / Number(stats._sum.declared_amount)) * 100).toFixed(0)
                         : "0";
                       
                       return (
                         <div key={type} className="space-y-4">
                            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">{type.toUpperCase()}</p>
                            <div className="flex items-end gap-3">
                               <span className="text-3xl font-black">{ratio}%</span>
                               <span className="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 whitespace-nowrap">Reliability</span>
                            </div>
                            <div className="h-1 bg-white/5 rounded-full overflow-hidden">
                               <div className="h-full bg-blue-500 rounded-full transition-all duration-1000" style={{ width: `${ratio}%` }} />
                            </div>
                         </div>
                       );
                    })}
                 </div>
              </div>
              <div className="absolute top-0 right-0 p-8 opacity-5">
                 <Building2 size={180} />
              </div>
           </div>
        </div>

        <div className="space-y-6">
           <div className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm relative overflow-hidden group italic">
              <h2 className="text-sm font-black uppercase tracking-widest mb-8 text-slate-900 border-b border-slate-50 pb-4">Rapid Ledger Entries</h2>
              <div className="space-y-4">
                 <Link href="/sales/cashbook/record?type=income&source=main" className="w-full p-6 bg-slate-50 hover:bg-slate-100 rounded-[1.5rem] border border-slate-100 flex items-center justify-between transition-all group shadow-sm">
                    <div className="text-left">
                       <p className="text-[11px] font-black uppercase tracking-widest text-slate-900 leading-none">Inflow Commit</p>
                       <p className="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-2 italic">Client Settlement</p>
                    </div>
                    <div className="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-emerald-600 transition-transform group-hover:scale-110">
                       <TrendingUp size={20} />
                    </div>
                 </Link>
                 <Link href="/sales/cashbook/record?type=expense&source=main" className="w-full p-6 bg-slate-50 hover:bg-slate-100 rounded-[1.5rem] border border-slate-100 flex items-center justify-between transition-all group shadow-sm">
                    <div className="text-left">
                       <p className="text-[11px] font-black uppercase tracking-widest text-slate-900 leading-none">Outflow Release</p>
                       <p className="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-2 italic">Operational Expense</p>
                    </div>
                    <div className="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-red-600 transition-transform group-hover:scale-110">
                       <TrendingDown size={20} />
                    </div>
                 </Link>
                 <Link href="/sales/cashbook/record?type=expense&source=petty" className="w-full p-6 bg-slate-50 hover:bg-slate-100 rounded-[1.5rem] border border-slate-100 flex items-center justify-between transition-all group shadow-sm">
                    <div className="text-left">
                       <p className="text-[11px] font-black uppercase tracking-widest text-slate-900 leading-none">Petty Logistics</p>
                       <p className="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-2 italic">Daily Office Opex</p>
                    </div>
                    <div className="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-amber-600 transition-transform group-hover:scale-110">
                       <Wallet size={20} />
                    </div>
                 </Link>
              </div>
           </div>

           <div className="bg-blue-600 rounded-[3rem] p-10 text-white relative overflow-hidden italic shadow-2xl">
              <div className="absolute top-0 right-0 p-8 opacity-10">
                 <ShieldCheck size={120} />
              </div>
              <h2 className="text-xl font-black uppercase tracking-tight mb-4 relative z-10 flex items-center gap-3">
                 <Lock size={20} />
                 Terminal Seal
              </h2>
              <p className="text-blue-100 text-[10px] font-bold uppercase tracking-[0.2em] mb-10 leading-relaxed opacity-80 relative z-10">
                Finalize today's fiscal ledger and archive the daily performance report to the MD command center.
              </p>
              <form action={closeBusinessDay} className="relative z-10">
                 <button className="w-full py-5 bg-white text-blue-900 rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                   Seal Day <ArrowRight size={16} />
                 </button>
              </form>
           </div>
        </div>
      </div>
    </div>
  );
}
