import { prisma } from "@/lib/prisma";
import { 
  Folder, 
  FileText, 
  Plus, 
  ArrowLeft, 
  Download, 
  Eye, 
  Import,
  Search,
  CheckCircle2,
  AlertCircle,
  FileIcon,
  ShieldCheck,
  Building2,
  Calendar,
  Lock,
  User,
  ArrowUpRight
} from "lucide-react";
import Link from "next/link";
import { uploadToCorporateFolder, importCrossPortalFiles } from "@/lib/vault-actions";
import { redirect } from "next/navigation";

export default async function MdFolderDetailPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;
  const folderId = parseInt(id);

  const folder = await prisma.corporateFolder.findUnique({
    where: { id: folderId },
    include: {
      files: {
        include: { uploader: true },
        orderBy: { created_at: "desc" }
      },
      client: true,
      organization: true,
      creator: true
    }
  });

  if (!folder) redirect("/md/folders");

  // Fetch potential files to import (EBMs, Tax, RRA, Tickets)
  const [ebms, taxRecords, rraFiles, tickets] = await Promise.all([
    prisma.ebm.findMany({ orderBy: { created_at: "desc" }, take: 20 }),
    prisma.taxCompliance.findMany({ orderBy: { created_at: "desc" }, take: 20 }),
    prisma.rraFile.findMany({ orderBy: { created_at: "desc" }, take: 20 }),
    prisma.ticket.findMany({ orderBy: { created_at: "desc" }, take: 20 }),
  ]);

  const availableImports = [
    ...ebms.map(e => ({ id: `ebm_${e.id}`, name: `EBM: ${e.company_name} - ${e.tin_number}`, source: "Sales / EBM", ref: `Sales / EBM||${e.company_name}||${e.file_path}` })),
    ...taxRecords.filter(t => t.declaration_file).map(t => ({ id: `tax_dec_${t.id}`, name: `${t.type} Tax Declaration - ${t.declaration_number}`, source: "Sales / Tax Hub", ref: `Sales / Tax Hub||${t.type} Declaration||${t.declaration_file}` })),
    ...taxRecords.filter(t => t.receipt_file).map(t => ({ id: `tax_rec_${t.id}`, name: `${t.type} Tax Receipt - ${t.declaration_number}`, source: "Sales / Tax Hub", ref: `Sales / Tax Hub||${t.type} Receipt||${t.receipt_file}` })),
    ...rraFiles.map(r => ({ id: `rra_${r.id}`, name: `RRA: ${r.type} ${r.month}/${r.year}`, source: "Sales / RRA Vault", ref: `Sales / RRA Vault||${r.type} Archive||${r.file_path}` })),
    ...tickets.map(t => ({ id: `ticket_${t.id}`, name: `Ticket: ${t.ticket_number} - ${t.client_names}`, source: "Ops / Tickets", ref: `Ops / Tickets||Ticket ${t.ticket_number}||${t.file_path}` })),
  ];

  return (
    <div className="space-y-10 pb-20 italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div className="flex items-center gap-6">
          <Link href="/md/folders" className="w-14 h-14 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
            <ArrowLeft size={20} />
          </Link>
          <div>
            <div className="flex items-center gap-2 mb-1">
                <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">{folder.type} Dossier</span>
                <span className="text-[10px] font-black text-slate-300">•</span>
                <span className="text-[10px] font-black text-emerald-500 uppercase tracking-widest flex items-center gap-1">
                    <ShieldCheck size={12} /> Secure Archive
                </span>
            </div>
            <h1 className="text-4xl font-black text-slate-900 tracking-tight uppercase">{folder.name}</h1>
          </div>
        </div>

        <div className="flex gap-4">
            <button 
                onClick={() => document.getElementById('import-modal')?.classList.remove('hidden')}
                className="px-8 py-4 bg-white border-2 border-slate-900 text-slate-900 rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:bg-slate-50 transition-all"
            >
                <Import size={18} /> Import from Portals
            </button>
            <button 
                onClick={() => document.getElementById('upload-modal')?.classList.remove('hidden')}
                className="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-xl active:scale-95"
            >
                <Plus size={18} strokeWidth={3} /> Upload to Folder
            </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-10">
        {/* Sidebar Info */}
        <div className="space-y-8">
            <div className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6">
                <h3 className="text-sm font-black uppercase tracking-widest text-slate-900 flex items-center gap-3 border-b border-slate-50 pb-4">
                    <Lock size={18} className="text-emerald-500" />
                    Folder Context
                </h3>
                <div className="space-y-4">
                    <div className="p-4 bg-slate-50 rounded-2xl">
                        <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Associated Entity</p>
                        <p className="text-xs font-black text-slate-900 uppercase truncate">
                            {folder.client?.name || folder.organization?.name || "Corporate General"}
                        </p>
                    </div>
                    <div className="p-4 bg-slate-50 rounded-2xl">
                        <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Folder Manager</p>
                        <div className="flex items-center gap-2">
                            <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[8px] font-black uppercase text-slate-600">
                                {folder.creator.name.charAt(0)}
                            </div>
                            <p className="text-xs font-black text-slate-900 uppercase truncate">{folder.creator.name}</p>
                        </div>
                    </div>
                    <div className="p-4 bg-slate-50 rounded-2xl">
                        <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Archive Integrity</p>
                        <p className="text-xs font-black text-slate-900 uppercase">{folder.files.length} Total Assets</p>
                    </div>
                </div>
            </div>

            <div className="bg-slate-900 p-8 rounded-[2.5rem] text-white italic shadow-2xl relative overflow-hidden">
                <div className="relative z-10">
                    <h3 className="text-sm font-black uppercase tracking-widest mb-4 flex items-center gap-3 text-emerald-400">
                        <ShieldCheck size={18} />
                        Security Protocol
                    </h3>
                    <p className="text-[10px] font-bold text-slate-400 leading-relaxed uppercase tracking-widest">
                        This folder contains sensitive documents. All access events are logged for MD audit compliance.
                    </p>
                </div>
                <div className="absolute right-[-10%] bottom-[-10%] opacity-10">
                    <Building2 size={120} />
                </div>
            </div>
        </div>

        {/* Assets List */}
        <div className="lg:col-span-3">
             <div className="bg-white rounded-[3rem] border border-slate-200 overflow-hidden shadow-sm">
                <div className="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Archived Assets</h3>
                    <div className="flex items-center gap-4">
                        <div className="relative">
                            <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={14} />
                            <input type="text" placeholder="Filter files..." className="pl-10 pr-4 py-2 bg-white border border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest focus:outline-none" />
                        </div>
                    </div>
                </div>
                <div className="divide-y divide-slate-100">
                    {folder.files.map((file) => (
                        <div key={file.id} className="p-8 hover:bg-slate-50 transition-all group flex items-center justify-between">
                            <div className="flex items-center gap-6">
                                <div className="w-14 h-14 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-300 group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900 transition-all shadow-sm">
                                    <FileIcon size={20} />
                                </div>
                                <div>
                                    <div className="flex items-center gap-2 mb-1">
                                        <span className={`text-[8px] font-black px-2 py-0.5 rounded uppercase tracking-widest ${file.file_type === 'uploaded' ? 'bg-blue-50 text-blue-500' : 'bg-emerald-50 text-emerald-500'}`}>
                                            {file.file_type}
                                        </span>
                                        <span className="text-[8px] font-black text-slate-300 uppercase tracking-widest flex items-center gap-1">
                                            <Calendar size={8} /> {new Date(file.created_at).toLocaleDateString()}
                                        </span>
                                    </div>
                                    <h4 className="text-lg font-black text-slate-900 uppercase tracking-tight">{file.name}</h4>
                                    <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Uploaded by {file.uploader.name}</p>
                                </div>
                            </div>

                            <div className="flex items-center gap-3">
                                <a 
                                    href={`/api/vault/folder-file/${file.id}`} 
                                    target="_blank"
                                    className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:border-slate-900 transition-all shadow-sm"
                                >
                                    <Eye size={18} />
                                </a>
                                <a 
                                    href={`/api/vault/folder-file/${file.id}`}
                                    download
                                    className="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center hover:scale-105 transition-all shadow-lg active:scale-95"
                                >
                                    <Download size={18} />
                                </a>
                            </div>
                        </div>
                    ))}

                    {folder.files.length === 0 && (
                        <div className="p-20 text-center space-y-4">
                            <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-200">
                                <Folder size={32} />
                            </div>
                            <p className="text-slate-400 font-black uppercase text-[10px] tracking-widest italic">No assets registered in this dossier yet</p>
                        </div>
                    )}
                </div>
             </div>
        </div>
      </div>

      {/* Upload Modal */}
      <div id="upload-modal" className="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 hidden flex items-center justify-center p-6 italic">
          <div className="bg-white rounded-[3rem] w-full max-w-lg overflow-hidden shadow-2xl relative">
              <div className="p-10 border-b border-slate-100 flex items-center justify-between">
                  <div>
                      <h2 className="text-2xl font-black text-slate-900 uppercase">File Ingestion</h2>
                      <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Adding assets to dossier directory</p>
                  </div>
                  <button onClick={() => document.getElementById('upload-modal')?.classList.add('hidden')} className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all">
                      <Plus size={20} className="rotate-45" />
                  </button>
              </div>
              <form action={uploadToCorporateFolder} className="p-10 space-y-8">
                  <input type="hidden" name="folder_id" value={folderId} />
                  <div className="space-y-3">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Label</label>
                      <input name="name" required placeholder="e.g. Scanned Q3 Contract Copy" type="text" className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic text-xs" />
                  </div>
                  <div className="space-y-3">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Select Physical File</label>
                      <input name="file" required type="file" className="w-full px-6 py-12 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl font-black text-slate-400 hover:border-slate-900 transition-all text-center uppercase text-[10px]" />
                  </div>
                  <div className="pt-4 flex items-center justify-end">
                      <button className="px-12 py-5 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl">
                        Ingest Asset <ArrowUpRight size={16} />
                      </button>
                  </div>
              </form>
          </div>
      </div>

      {/* Import Modal */}
      <div id="import-modal" className="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 hidden flex items-center justify-center p-6 italic">
          <div className="bg-white rounded-[3rem] w-full max-w-2xl overflow-hidden shadow-2xl relative">
              <div className="p-10 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                  <div>
                      <h2 className="text-2xl font-black text-slate-900 uppercase">Cross-Portal Import</h2>
                      <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Retrieve certified assets from other ecosystem gates</p>
                  </div>
                  <button onClick={() => document.getElementById('import-modal')?.classList.add('hidden')} className="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-all">
                      <Plus size={20} className="rotate-45" />
                  </button>
              </div>
              
              <div className="max-h-[50vh] overflow-y-auto">
                    <table className="w-full text-left border-collapse">
                        <thead className="sticky top-0 bg-white shadow-sm z-10">
                            <tr className="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                <th className="px-8 py-4">Portal Asset</th>
                                <th className="px-8 py-4">Source Gate</th>
                                <th className="px-8 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {availableImports.map((imp) => (
                                <tr key={imp.id} className="hover:bg-blue-50/30 transition-all group">
                                    <td className="px-8 py-4">
                                        <p className="text-xs font-black text-slate-900 uppercase truncate max-w-[250px]">{imp.name}</p>
                                    </td>
                                    <td className="px-8 py-4">
                                        <span className="text-[9px] font-black bg-slate-100 px-2 py-1 rounded text-slate-500 uppercase tracking-widest">{imp.source}</span>
                                    </td>
                                    <td className="px-8 py-4 text-right">
                                        <form action={async (fd) => {
                                            "use server";
                                            const ref = fd.get("ref") as string;
                                            await importCrossPortalFiles(folderId, [ref]);
                                        }}>
                                            <input type="hidden" name="ref" value={imp.ref} />
                                            <button className="text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-900 flex items-center gap-2 ml-auto group-hover:scale-105 transition-all">
                                                Fetch <Import size={12} />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
              </div>

              <div className="p-8 border-t border-slate-100 flex items-center justify-between text-slate-400">
                  <div className="flex items-center gap-2">
                      <ShieldCheck size={14} className="text-emerald-500" />
                      <span className="text-[9px] font-black uppercase tracking-widest">End-to-End Encryption Maintained</span>
                  </div>
                  <p className="text-[9px] font-black uppercase tracking-widest">{availableImports.length} Searchable Portal Assets</p>
              </div>
          </div>
      </div>
    </div>
  );
}
