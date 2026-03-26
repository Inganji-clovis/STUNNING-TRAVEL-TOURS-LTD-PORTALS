"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
import { 
  Plus, 
  X, 
  Plane, 
  Calendar, 
  User, 
  Hash, 
  FileText, 
  Save, 
  ShieldCheck,
  Upload,
  Info
} from "lucide-react";
import { createTicket } from "@/lib/ticket-actions";

export default function TicketForm() {
  const router = useRouter();
  const [isOpen, setIsOpen] = useState(false);
  const [isPending, setIsPending] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await createTicket(formData);
    setIsPending(false);

    if (result.success) {
      toast.success(result.message);
      setIsOpen(false);
      router.refresh();
    } else {
      toast.error(result.message);
    }
  }

  return (
    <>
      <button 
        onClick={() => setIsOpen(true)}
        className="flex items-center gap-3 px-8 py-4 bg-emerald-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-emerald-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all italic"
      >
        <Plus size={16} /> Record New Ticket
      </button>

      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 font-outfit uppercase font-black uppercase">
          <div className="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-emerald-50/50">
              <div className="flex items-center gap-4 text-emerald-600">
                <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-emerald-100">
                  <Plane size={24} />
                </div>
                <div>
                  <h2 className="text-2xl font-black uppercase tracking-tight italic uppercase">Ticket Issuance</h2>
                  <p className="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mt-1 italic uppercase">Flight Registry & PNR Archive</p>
                </div>
              </div>
              <button onClick={() => setIsOpen(false)} className="p-2 hover:bg-emerald-100 rounded-full transition-colors">
                <X size={20} className="text-emerald-300" />
              </button>
            </div>

            <form action={handleSubmit} className="p-8 space-y-8 overflow-y-auto italic font-black">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8 font-black">
                <div className="md:col-span-2 space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Client Name(s)</label>
                  <div className="relative">
                    <User className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input name="client_names" required placeholder="Passenger Names (Separated by comma)" className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs shadow-inner" />
                  </div>
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">PNR Code</label>
                  <input name="pnr" required placeholder="Ex: ABCDEF" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs tracking-[0.2em]" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Ticket Number</label>
                  <input name="ticket_number" required placeholder="Ex: 000-1234..." className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Airline</label>
                  <input name="airline_name" required placeholder="Ex: RWANDAIR" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Flight Number</label>
                  <input name="flight_number" required placeholder="Ex: WB102" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Departure Date</label>
                  <input type="date" name="departure_date" required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Return Date (Optional)</label>
                  <input type="date" name="return_date" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="md:col-span-2 space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Operational Remarks</label>
                  <textarea name="remarks" placeholder="Ex: Early check-in requested..." className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs min-h-[80px]"></textarea>
                </div>
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Ticket Scan / E-Ticket PDF</label>
                <div className="relative group/file">
                  <input type="file" name="file" required className="absolute inset-0 opacity-0 cursor-pointer z-10" />
                  <div className="border-2 border-dashed border-slate-200 rounded-[2rem] p-10 flex flex-col items-center gap-4 group-hover/file:border-emerald-400 transition-all bg-slate-50/50">
                    <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-300 group-hover/file:text-emerald-500 shadow-sm transition-colors">
                      <Upload size={24} />
                    </div>
                    <div className="text-center">
                      <p className="text-[10px] font-black text-slate-900 uppercase tracking-widest">Select Ticket PDF</p>
                      <p className="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Archived securely for airline verification</p>
                    </div>
                  </div>
                </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <ShieldCheck size={16} className="text-emerald-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Operational Ledger Sync</span>
                 </div>
                 <button 
                  disabled={isPending}
                  className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl active:scale-95 disabled:opacity-50"
                 >
                    {isPending ? "Validating..." : <><Save size={18} /> Archive Ticket</>}
                 </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
}
