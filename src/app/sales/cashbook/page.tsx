import { prisma } from "@/lib/prisma";
import { 
  Plus, 
  Search, 
  Filter, 
  ArrowUpRight, 
  ArrowDownRight, 
  Wallet, 
  Clock, 
  History, 
  TrendingUp, 
  TrendingDown, 
  DollarSign, 
  Coins, 
  ShieldCheck, 
  Lock, 
  ChevronRight,
  Calculator
} from "lucide-react";
import Link from "next/link";
import { closeBusinessDay } from "@/lib/cashbook-actions";
import OpenDayForm from "@/components/sales/OpenDayForm";

export default async function CashbookPage() {
  const [cashDay, lastClosedDay, transactions] = await Promise.all([
    prisma.cashDay.findFirst({
      where: { status: 'open' },
      orderBy: { date: 'desc' },
    }),
    prisma.cashDay.findFirst({
      where: { status: 'closed' },
      orderBy: { date: 'desc' },
    }),
    prisma.cashTransaction.findMany({
      take: 20,
      orderBy: { created_at: 'desc' },
      include: { category: true, creator: true }
    })
  ]);

  const suggestedBalances = {
    opening_balance: Number(lastClosedDay?.closing_balance || 0),
    opening_petty_balance: Number(lastClosedDay?.closing_petty_balance || 0),
    opening_balance_usd: Number(lastClosedDay?.closing_balance_usd || 0),
    opening_petty_balance_usd: Number(lastClosedDay?.closing_petty_balance_usd || 0),
  };

  return (
    <div className="space-y-8 font-outfit uppercase font-black">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Macro-Finance Ledger</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic">Real-Time Treasury & Cashflow Control</p>
        </div>
        <div className="flex gap-4">
           {cashDay && (
             <div className="flex gap-2">
                <Link href="/sales/cashbook/record?type=income&source=main" className="flex items-center gap-3 px-6 py-4 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-widest rounded-2xl shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all border border-emerald-500 italic">
                  <Plus size={14} /> Record Income
                </Link>
                <Link href="/sales/cashbook/record?type=expense&source=main" className="flex items-center gap-3 px-6 py-4 bg-red-600 text-white text-[9px] font-black uppercase tracking-widest rounded-2xl shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all border border-red-500 italic">
                  <Plus size={14} /> Record Expense
                </Link>
                <Link href="/sales/cashbook/record?type=expense&source=petty" className="flex items-center gap-3 px-6 py-4 bg-amber-600 text-white text-[9px] font-black uppercase tracking-widest rounded-2xl shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all border border-amber-500 italic">
                  <Wallet size={14} /> Petty Cash
                </Link>
             </div>
           )}
           {cashDay && (
             <form action={closeBusinessDay}>
                <input type="hidden" name="day_id" value={cashDay.id} />
                <button className="flex items-center gap-3 px-8 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all border border-slate-800">
                  <Lock size={16} /> Close Day
                </button>
             </form>
           )}
        </div>
      </div>

      {/* Stats Dashboard */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden group">
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Main Balance (RWF)</p>
          <p className="text-2xl font-black text-slate-900 italic">
            {cashDay ? Number(cashDay.closing_balance).toLocaleString() : 'CLOSED'}
          </p>
          <div className="mt-4 flex items-center gap-2">
             <div className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" />
             <span className="text-[9px] font-black text-emerald-600 uppercase italic tracking-widest">Liquid Assets</span>
          </div>
        </div>

        <div className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden group">
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Main Balance (USD)</p>
          <p className="text-2xl font-black text-slate-900 italic">
            $ {cashDay ? Number(cashDay.closing_balance_usd).toLocaleString() : '0.00'}
          </p>
          <div className="mt-4 flex items-center gap-2">
             <div className="w-1.5 h-1.5 rounded-full bg-blue-500" />
             <span className="text-[9px] font-black text-blue-600 uppercase italic tracking-widest">Forex Reserves</span>
          </div>
        </div>

        <div className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden group border-l-4 border-l-amber-500">
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Petty Cash (RWF)</p>
          <p className="text-2xl font-black text-slate-900 italic">
            {cashDay ? Number(cashDay.closing_petty_balance).toLocaleString() : '0'}
          </p>
          <div className="mt-4 flex items-center gap-2 text-amber-600">
             <Clock size={12} />
             <span className="text-[9px] font-black uppercase italic tracking-widest">Daily OPEX Pool</span>
          </div>
        </div>

        <div className="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl relative overflow-hidden group text-white italic">
          <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Platform Integrity</p>
          <p className="text-2xl font-black text-emerald-400 uppercase tracking-tight">{cashDay ? "LIVE" : "LOCKED"}</p>
          <div className="mt-4 flex items-center gap-2 text-slate-400">
             <ShieldCheck size={12} />
             <span className="text-[9px] font-black uppercase tracking-widest">Audited Locally</span>
          </div>
        </div>
      </div>

      {/* Operational Banner */}
      <div className={`p-8 rounded-[2.5rem] border-2 ${cashDay ? 'bg-emerald-50/30 border-emerald-100' : 'bg-red-50/30 border-red-100'} flex items-center justify-between shadow-sm`}>
          <div className="flex items-center gap-6">
             <div className={`w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg border-2 ${cashDay ? 'bg-emerald-600 border-emerald-500 text-white' : 'bg-red-600 border-red-500 text-white'}`}>
                <Wallet size={24} />
             </div>
             <div>
                <h4 className={`text-md font-black uppercase tracking-tight italic ${cashDay ? 'text-emerald-900' : 'text-red-900'}`}>
                   {cashDay ? `BUSINESS DAY OPEN: ${new Date(cashDay.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' })}` : "TRADING SUSPENDED: CASH DAY CLOSED"}
                </h4>
                <p className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1 italic">
                   {cashDay ? "Current liquidity reflects opening balance + intra-day settlements" : "Opening of new treasury session required to commit transactions"}
                </p>
             </div>
          </div>
          {!cashDay && (
            <OpenDayForm suggested={suggestedBalances} />
          )}
      </div>

      {/* Ledger Table */}
      <div className="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden min-h-[500px]">
        <div className="p-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
           <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-4 italic font-outfit">
              <History size={24} className="text-blue-600" />
              Unified Transaction Ledger
           </h2>
           <div className="flex gap-3">
              <div className="relative">
                 <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                 <input type="text" placeholder="Search entries..." className="pl-12 pr-6 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[10px] font-black uppercase w-64 italic focus:outline-none focus:border-slate-900" />
              </div>
           </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-slate-50/50 border-b border-slate-100">
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Timecode</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Beneficiary / Category</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Segment</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-center">Protocol</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Value</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {transactions.map((tx) => (
                <tr key={tx.id} className="hover:bg-slate-50/50 transition-all group">
                  <td className="px-10 py-8">
                     <p className="text-[11px] font-black text-slate-900 uppercase italic leading-none">{new Date(tx.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                     <p className="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 italic">{new Date(tx.created_at).toLocaleDateString()}</p>
                  </td>
                  <td className="px-10 py-8">
                    <div className="flex items-center gap-4">
                      <div className={`w-12 h-12 rounded-2xl flex items-center justify-center border shadow-sm ${
                        tx.type === 'income' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-red-50 border-red-100 text-red-600'
                      }`}>
                        {tx.type === 'income' ? <TrendingUp size={20} /> : <TrendingDown size={20} />}
                      </div>
                      <div>
                        <p className="text-sm font-black text-slate-900 uppercase italic tracking-tight italic leading-none">{tx.category.name}</p>
                        <p className="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2 italic truncate max-w-[200px]">{tx.description || 'System Audit Entry'}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-10 py-8">
                     <span className={`px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border italic ${
                        tx.source === 'petty' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-blue-50 text-blue-600 border-blue-100'
                     }`}>
                        {tx.source} cash
                     </span>
                  </td>
                  <td className="px-10 py-8 text-center text-[10px] font-black text-slate-400 uppercase italic tracking-[0.2em]">
                     {tx.payment_method || 'DIGITAL'}
                  </td>
                  <td className="px-10 py-8 text-right">
                    <p className={`text-lg font-black italic tracking-tighter ${tx.type === 'income' ? 'text-emerald-600' : 'text-red-600'}`}>
                      {tx.type === 'income' ? '+' : '-'} {Number(tx.amount).toLocaleString()}
                    </p>
                    <p className="text-[9px] font-black text-slate-300 uppercase tracking-widest mt-1 italic">{tx.currency}</p>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
