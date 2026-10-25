/**
 * Barra superior de la aplicación.
 */
export default function Navbar() {
  return (
    <header className="bg-decameron text-white shadow">
      <div className="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
        <div className="flex items-center gap-2">
          <span className="text-xl font-bold tracking-tight">Decameron</span>
          <span className="hidden text-sm text-blue-100 sm:inline">
            · Gestión Hotelera
          </span>
        </div>
        <span className="text-sm text-blue-100">Dirección de Operaciones</span>
      </div>
    </header>
  );
}
