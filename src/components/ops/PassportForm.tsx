"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
import { 
  Plus, 
  X, 
  ShieldCheck, 
  Upload, 
  User, 
  Globe, 
  Calendar, 
  MapPin,
  Save,
  Fingerprint
} from "lucide-react";
import { uploadPassport } from "@/lib/passport-actions";

interface PassportFormProps {
  clients: { id: number; name: string }[];
}

export default function PassportForm({ clients }: PassportFormProps) {
  const router = useRouter();
  const [isOpen, setIsOpen] = useState(false);
  const [isPending, setIsPending] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await uploadPassport(formData);
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
        className="flex items-center gap-3 px-8 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-indigo-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
      >
        <Plus size={16} /> Archive New Passport
      </button>

      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 font-outfit uppercase font-black">
          <div className="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-indigo-50/50">
              <div className="flex items-center gap-4 text-indigo-600">
                <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-indigo-100">
                  <Fingerprint size={24} />
                </div>
                <div>
                  <h2 className="text-2xl font-black uppercase tracking-tight italic">Passport Vaulting</h2>
                  <p className="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mt-1">Biometric Document Archival Protocol</p>
                </div>
              </div>
              <button onClick={() => setIsOpen(false)} className="p-2 hover:bg-indigo-100 rounded-full transition-colors">
                <X size={20} className="text-indigo-300" />
              </button>
            </div>

            <form action={handleSubmit} className="p-8 space-y-8 overflow-y-auto italic font-black">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Select Client</label>
                  <select name="client_id" required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs">
                    <option value="">Choose Client...</option>
                    {clients.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                  </select>
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Passport Number</label>
                  <input name="passport_number" required placeholder="Ex: PC123456" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nationality</label>
                  <input name="nationality" placeholder="Ex: RWANDAN" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Date of Birth</label>
                  <input type="date" name="dob" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Issue Date</label>
                    <input type="date" name="issue_date" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Expiry Date</label>
                    <input type="date" name="expiry_date" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Place of Issue</label>
                  <input name="place_of_issue" placeholder="Ex: DGIE KIGALI" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Gender</label>
                  <select name="gender" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs">
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                    <option value="O">Other</option>
                  </select>
                </div>
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Passport Scan / PDF</label>
                <div className="relative group/file">
                  <input type="file" name="file" required className="absolute inset-0 opacity-0 cursor-pointer z-10" />
                  <div className="border-2 border-dashed border-slate-200 rounded-[2rem] p-10 flex flex-col items-center gap-4 group-hover/file:border-indigo-400 transition-all bg-slate-50/50">
                    <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-300 group-hover/file:text-indigo-500 shadow-sm transition-colors">
                      <Upload size={24} />
                    </div>
                    <div className="text-center">
                      <p className="text-[10px] font-black text-slate-900 uppercase tracking-widest">Select Passport File</p>
                      <p className="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Archives securely to Google Drive</p>
                    </div>
                  </div>
                </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <ShieldCheck size={16} className="text-indigo-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Encrypted Sovereign Storage</span>
                 </div>
                 <button 
                  disabled={isPending}
                  className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl active:scale-95 disabled:opacity-50"
                 >
                    {isPending ? "Configuring Vault..." : <><Save size={18} /> Seal Document</>}
                 </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
}
