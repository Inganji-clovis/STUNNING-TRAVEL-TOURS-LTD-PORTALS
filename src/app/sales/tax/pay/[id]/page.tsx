import { prisma } from "@/lib/prisma";
import { notFound } from "next/navigation";
import PayTaxForm from "@/components/sales/PayTaxForm";

export default async function PayTaxPageWrapper({ params }: { params: Promise<{ id: string }> }) {
  const { id } = await params;
  const taxRecord = await prisma.taxCompliance.findUnique({
    where: { id: parseInt(id) }
  });

  if (!taxRecord) notFound();

  return <PayTaxForm params={params} taxRecord={taxRecord} />;
}
