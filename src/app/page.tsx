import { auth } from "@/auth";
import { redirect } from "next/navigation";

export default async function Home() {
  const session = await auth();

  if (!session) {
    redirect("/login");
  }

  const role = (session.user as any).role;

  switch (role) {
    case "admin":
      redirect("/admin");
    case "sales":
      redirect("/sales");
    case "operations":
      redirect("/ops");
    case "md":
      redirect("/md");
    default:
      redirect("/login");
  }
}
