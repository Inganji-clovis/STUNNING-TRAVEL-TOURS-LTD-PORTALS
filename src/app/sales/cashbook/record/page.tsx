import { prisma } from "@/lib/prisma";
import { 
  ArrowLeft, 
  Plus, 
  ArrowUpRight, 
  ArrowDownRight, 
  Wallet, 
  Coins, 
  DollarSign,
  Briefcase,
  Info,
  Save,
  Plane,
  ShieldCheck
} from "lucide-react";
import Link from "next/link";
import { recordTransaction } from "@/lib/cashbook-actions";

export default async function RecordTransactionPage({
  searchParams,
}: {
  searchParams: Promise<{ type?: string; source?: string }>;
}) {
  const { type = "income", source = "main" } = await searchParams;
  const categories = await prisma.cashCategory.findMany({
    where: { is_active: true, type }
  });

  return (
    <div className="max-w-4xl mx-auto space-y-8 pb-20">
      {/* Header */}
      <div className="flex items-center gap-6">
        <Link href="/sales/cashbook" className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
           <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic uppercase">Record Entry</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1 italic uppercase">Macro-Finance Ledger Commit</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Form Column */}
        <div className="lg:col-span-2">
           <form action={recordTransaction} className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm space-y-8 italic">
              <input type="hidden" name="type" value={type} />
              
              {/* Type & Source Selector (Visual Labels) */}
              <div className="grid grid-cols-2 gap-4">
                 <div className={`p-6 rounded-2xl border ${type === 'income' ? 'bg-emerald-50 border-emerald-100 text-emerald-900' : 'bg-red-50 border-red-100 text-red-900'} relative`}>
                    <p className="text-[9px] font-black uppercase tracking-widest opacity-60">Entry Vector</p>
                    <p className="font-black uppercase tracking-tight text-lg mt-1">{type}</p>
                    <div className="absolute right-6 top-1/2 -translate-y-1/2 opacity-20">
                       {type === 'income' ? <ArrowUpRight size={32} /> : <ArrowDownRight size={32} />}
                    </div>
                 </div>
                 <div className={`p-6 rounded-2xl border bg-blue-50 border-blue-100 text-blue-900 relative`}>
                    <input type="hidden" name="source" value={source} />
                    <p className="text-[9px] font-black uppercase tracking-widest opacity-60">Target Reserve</p>
                    <p className="font-black uppercase tracking-tight text-lg mt-1">{source} cash</p>
                    <div className="absolute right-6 top-1/2 -translate-y-1/2 opacity-20">
                       <Wallet size={32} />
                    </div>
                 </div>
              </div>

              <div className="space-y-8">
                 <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div className="space-y-3">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Currency</label>
                       <div className="flex gap-2">
                          <label className="flex-1 cursor-pointer">
                             <input type="radio" name="currency" value="RWF" defaultChecked className="hidden peer" />
                             <div className="w-full py-4 text-center bg-slate-50 border border-slate-100 rounded-2xl font-black text-xs peer-checked:bg-slate-900 peer-checked:text-white transition-all uppercase italic">RWF</div>
                          </label>
                          <label className="flex-1 cursor-pointer">
                             <input type="radio" name="currency" value="USD" className="hidden peer" />
                             <div className="w-full py-4 text-center bg-slate-50 border border-slate-100 rounded-2xl font-black text-xs peer-checked:bg-slate-900 peer-checked:text-white transition-all uppercase italic">USD</div>
                          </label>
                       </div>
                    </div>

                    <div className="space-y-3">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Transaction Category</label>
                       <select name="category_id" required className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic">
                          {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                       </select>
                    </div>

                    <div className="space-y-3">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Settlement Method</label>
                       <select name="payment_method" required className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic">
                          <option value="Cash">Cash Handover</option>
                          <option value="Bank">Bank Transfer</option>
                          <option value="MoMo">Mobile Money</option>
                          <option value="Cheque">Cheque</option>
                       </select>
                    </div>

                    <div className="space-y-3">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Transaction Value</label>
                       <div className="relative">
                          <Coins className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                          <input 
                            name="amount"
                            type="number" 
                            required
                            placeholder="0.00"
                            className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all italic"
                          />
                       </div>
                    </div>
                 </div>

                 {/* Specialized Ticket Fields */}
                 <div className="p-8 bg-blue-50/30 rounded-3xl border border-blue-100/50 space-y-6">
                    <div className="flex items-center gap-3 mb-2">
                       <Plane size={18} className="text-blue-600" />
                       <h3 className="text-[10px] font-black text-blue-900 uppercase tracking-widest italic">Ticket Sales Protocol (Optional)</h3>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                       <div className="space-y-2">
                          <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">TTC (Ticket Cost)</label>
                          <input name="ttc" type="number" step="0.01" className="w-full px-5 py-3 bg-white border border-slate-100 rounded-xl font-bold text-xs" />
                       </div>
                       <div className="space-y-2">
                          <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Service Fee (Profit)</label>
                          <input name="service_fee" type="number" step="0.01" className="w-full px-5 py-3 bg-white border border-slate-100 rounded-xl font-bold text-xs" />
                       </div>
                    </div>
                 </div>

                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Internal Reference / Description</label>
                    <textarea 
                      name="description"
                      rows={3}
                      placeholder="Enter detailed transaction notes..."
                      className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase placeholder:text-slate-300 italic text-xs leading-relaxed"
                    ></textarea>
                 </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <ShieldCheck size={16} className="text-emerald-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Ledger Integrity Verified</span>
                 </div>
                 <button className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl active:scale-95">
                    <Save size={18} /> Commit Transaction
                 </button>
              </div>
           </form>
        </div>

        {/* Sidebar Info */}
        <div className="space-y-8">
           <div className="bg-slate-900 p-8 rounded-[2rem] text-white italic shadow-xl relative overflow-hidden">
              <div className="relative z-10">
                 <h3 className="text-sm font-black uppercase tracking-widest mb-6 border-b border-white/5 pb-4 flex items-center gap-3">
                    <Info size={18} className="text-blue-400" />
                    Protocol Help
                 </h3>
                 <div className="space-y-6">
                    <div>
                       <p className="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Main vs Petty</p>
                       <p className="text-[10px] font-bold text-slate-400 uppercase leading-relaxed tracking-widest">
                          Main cash tracks operational liquidity. Petty cash is for daily office OPEX and must be replenished from main.
                       </p>
                    </div>
                    <div>
                       <p className="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Ticket Inflow</p>
                       <p className="text-[10px] font-bold text-slate-400 uppercase leading-relaxed tracking-widest">
                          RC (Rate Collected) must equal TTC + Service Fee. Only Service Fee counts towards company profit.
                       </p>
                    </div>
                 </div>
              </div>
              <div className="absolute top-0 right-0 p-8 opacity-10">
                 <DollarSign size={150} />
              </div>
           </div>
        </div>
      </div>
    </div>
  );
}
