"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
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

interface TransactionFormProps {
  type: string;
  source: string;
  categories: { id: number; name: string }[];
  cashDayId: number;
}

export default function TransactionForm({ type, source, categories, cashDayId }: TransactionFormProps) {
  const router = useRouter();
  const [isPending, setIsPending] = useState(false);
  const [isTicket, setIsTicket] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await recordTransaction(formData);
    setIsPending(false);

    if (result.success) {
      toast.success(result.message);
      router.push("/sales/cashbook");
      router.refresh();
    } else {
      toast.error(result.message);
    }
  }

  return (
    <form action={handleSubmit} className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm space-y-8 italic font-black uppercase tracking-tight">
      <input type="hidden" name="type" value={type} />
      <input type="hidden" name="source" value={source} />
      <input type="hidden" name="cash_day_id" value={cashDayId} />
      
      <div className="grid grid-cols-2 gap-4">
         <div className={`p-6 rounded-2xl border ${type === 'income' ? 'bg-emerald-50 border-emerald-100 text-emerald-900' : 'bg-red-50 border-red-100 text-red-900'} relative`}>
            <p className="text-[9px] font-black uppercase tracking-widest opacity-60">Entry Vector</p>
            <p className="font-black uppercase tracking-tight text-lg mt-1 italic">{type}</p>
            <div className="absolute right-6 top-1/2 -translate-y-1/2 opacity-20">
               {type === 'income' ? <ArrowUpRight size={32} /> : <ArrowDownRight size={32} />}
            </div>
         </div>
         <div className={`p-6 rounded-2xl border bg-blue-50 border-blue-100 text-blue-900 relative`}>
            <p className="text-[9px] font-black uppercase tracking-widest opacity-60">Target Reserve</p>
            <p className="font-black uppercase tracking-tight text-lg mt-1 italic">{source} cash</p>
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
               <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Category</label>
               <select 
                name="category_id" 
                required 
                onChange={(e) => {
                  const cat = categories.find(c => c.id === parseInt(e.target.value));
                  setIsTicket(cat?.name.toLowerCase().includes('ticket') || false);
                }}
                className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 text-xs focus:outline-none focus:border-slate-900 transition-all uppercase italic"
               >
                  <option value="">Select Category</option>
                  {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
               </select>
            </div>

            <div className="space-y-3">
               <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Settlement Method</label>
               <select name="payment_method" required className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 text-xs focus:outline-none focus:border-slate-900 transition-all uppercase italic">
                  <option value="Cash">Cash Handover</option>
                  <option value="Bank">Bank Transfer</option>
                  <option value="MoMo">Mobile Money</option>
                  <option value="Cheque">Cheque</option>
               </select>
            </div>

            <div className="space-y-3">
               <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Transaction Value (RC)</label>
               <div className="relative">
                  <Coins className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                  <input 
                    name="amount"
                    type="number" 
                    required
                    placeholder="0.00"
                    className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 text-xs focus:outline-none focus:border-slate-900 transition-all italic"
                  />
               </div>
            </div>
         </div>

         {isTicket && (
           <div className="p-8 bg-blue-50/50 rounded-[2rem] border-2 border-blue-100 space-y-6 animate-in fade-in slide-in-from-top-4 duration-500">
              <div className="flex items-center gap-3 mb-2">
                 <div className="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg">
                    <Plane size={16} />
                 </div>
                 <h3 className="text-[10px] font-black text-blue-900 uppercase tracking-widest italic">Airticket Settlement Validation</h3>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div className="space-y-2">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">TTC (Ticket Cost)</label>
                    <input name="ttc" type="number" step="0.01" required className="w-full px-5 py-4 bg-white border border-slate-200 rounded-xl font-black text-xs italic" />
                 </div>
                 <div className="space-y-2">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Service Fee (Profit)</label>
                    <input name="service_fee" type="number" step="0.01" required className="w-full px-5 py-4 bg-white border border-slate-200 rounded-xl font-black text-xs italic shadow-inner" />
                 </div>
              </div>
              <div className="flex items-center gap-3 p-4 bg-blue-100/50 rounded-xl border border-blue-200/50">
                 <Info size={14} className="text-blue-600" />
                 <p className="text-[8px] font-black text-blue-600 uppercase tracking-widest italic">Constraint: Value (RC) MUST EQUAL TTC + Service Fee</p>
              </div>
           </div>
         )}

         <div className="space-y-3">
            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Internal Reference / Description</label>
            <textarea 
              name="description"
              rows={3}
              placeholder="Enter detailed transaction context..."
              className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase placeholder:text-slate-200 italic text-xs leading-relaxed"
            ></textarea>
         </div>
      </div>

      <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
         <div className="flex items-center gap-2">
            <ShieldCheck size={16} className="text-emerald-500" />
            <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Integrity Protocol Active</span>
         </div>
         <button 
          disabled={isPending}
          className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] flex items-center gap-3 hover:scale-[1.05] transition-all shadow-2xl active:scale-95 disabled:opacity-50"
         >
            {isPending ? "Validating..." : <><Save size={18} /> Commit to Ledger</>}
         </button>
      </div>
    </form>
  );
}
