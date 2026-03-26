"use client";

import { useState } from "react";
import { toast } from "sonner";
import { Plus, X, Wallet, ShieldCheck, ChevronRight, Calculator } from "lucide-react";
import { openBusinessDay } from "@/lib/cashbook-actions";

interface OpenDayFormProps {
  suggested: {
    opening_balance: number;
    opening_petty_balance: number;
    opening_balance_usd: number;
    opening_petty_balance_usd: number;
  };
}

export default function OpenDayForm({ suggested }: OpenDayFormProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [isPending, setIsPending] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await openBusinessDay(formData);
    setIsPending(false);

    if (result.success) {
      toast.success(result.message);
      setIsOpen(false);
    } else {
      toast.error(result.message);
    }
  }

  const today = new Date().toISOString().split('T')[0];

  return (
    <>
      <button 
        onClick={() => setIsOpen(true)}
        className="px-8 py-3 bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-700 transition-all flex items-center gap-2 italic shadow-lg shadow-red-900/20 active:scale-95"
      >
        Initiate Day <ChevronRight size={14} />
      </button>

      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 italic uppercase font-black tracking-tight">
          <div className="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-red-50/50">
              <div className="flex items-center gap-4 text-red-600">
                <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-red-100">
                  <Calculator size={24} />
                </div>
                <div>
                  <h2 className="text-2xl font-black uppercase tracking-tight italic">Initiate Business Cycle</h2>
                  <p className="text-[10px] font-bold text-red-400 uppercase tracking-widest mt-1">Audit Balances & Confirm Treasury Open</p>
                </div>
              </div>
              <button onClick={() => setIsOpen(false)} className="p-2 hover:bg-red-100 rounded-full transition-colors">
                <X size={20} className="text-red-300" />
              </button>
            </div>

            <form action={handleSubmit} className="p-8 space-y-8 overflow-y-auto">
              <div className="space-y-4">
                 <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Session Date</label>
                 <input type="date" name="date" defaultValue={today} required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900" />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {/* Main Cash */}
                <div className="space-y-6">
                   <h3 className="text-[11px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2">
                      <div className="w-1.5 h-1.5 rounded-full bg-blue-600" /> Main Ledger Opening
                   </h3>
                   <div className="space-y-4">
                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">RWF Balance</label>
                        <input type="number" name="opening_balance" defaultValue={suggested.opening_balance} required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900" />
                      </div>
                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">USD Balance</label>
                        <input type="number" name="opening_balance_usd" defaultValue={suggested.opening_balance_usd} required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900" />
                      </div>
                   </div>
                </div>

                {/* Petty Cash */}
                <div className="space-y-6">
                   <h3 className="text-[11px] font-black text-amber-600 uppercase tracking-widest flex items-center gap-2">
                      <div className="w-1.5 h-1.5 rounded-full bg-amber-600" /> Petty Cash Opening
                   </h3>
                   <div className="space-y-4">
                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">RWF Balance</label>
                        <input type="number" name="opening_petty_balance" defaultValue={suggested.opening_petty_balance} required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900" />
                      </div>
                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">USD Balance</label>
                        <input type="number" name="opening_petty_balance_usd" defaultValue={suggested.opening_petty_balance_usd} required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900" />
                      </div>
                   </div>
                </div>
              </div>

              <div className="p-6 bg-slate-900 rounded-3xl flex items-center gap-6">
                 <div className="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-emerald-400">
                    <ShieldCheck size={24} />
                 </div>
                 <div className="flex-1">
                    <p className="text-[10px] font-black text-white uppercase tracking-widest">Audit Policy Agreement</p>
                    <p className="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">I confirm these opening balances match the physical cash vault counts.</p>
                 </div>
              </div>

              <div className="flex justify-end pt-4">
                <button 
                  disabled={isPending}
                  className="w-full py-5 bg-red-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] flex items-center justify-center gap-3 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-2xl shadow-red-900/30 disabled:opacity-50"
                >
                  {isPending ? "Configuring Ledger..." : "Open Treasury Session"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
}
