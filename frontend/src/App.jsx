import { useState } from 'react';
import Navbar from './components/common/Navbar';
import Modal from './components/common/Modal';
import Alert from './components/common/Alert';
import HotelForm from './components/hotel/HotelForm';
import HotelList from './components/hotel/HotelList';
import RoomAssignmentForm from './components/room/RoomAssignmentForm';
import { useHotels } from './hooks/useHotels';
import { useCatalogs } from './hooks/useCatalogs';
import { HotelService } from './services/api';
import { extractApiError } from './utils/validators';

/**
 * Componente raíz. Compone la lista de hoteles, el formulario de registro y
 * el modal de asignación de habitaciones. Mantiene el estado de alto nivel
 * (mensajes flash y hotel seleccionado) y delega el resto a los componentes.
 */
export default function App() {
  const { hotels, loading, error, refresh } = useHotels();
  const { catalogs, loading: loadingCatalogs } = useCatalogs();
  const [selected, setSelected] = useState(null);
  const [flash, setFlash] = useState(null);

  const handleCreated = () => {
    setFlash('Hotel registrado correctamente.');
    refresh();
  };

  const handleAssigned = () => {
    setFlash('Habitaciones asignadas correctamente.');
    setSelected(null);
    refresh();
  };

  const handleDelete = async (hotel) => {
    if (!window.confirm(`¿Eliminar el hotel "${hotel.name}"?`)) return;
    try {
      await HotelService.remove(hotel.id);
      setFlash('Hotel eliminado.');
      refresh();
    } catch (err) {
      setFlash(extractApiError(err));
    }
  };

  return (
    <div className="min-h-screen">
      <Navbar />

      <main className="mx-auto max-w-6xl px-4 py-8">
        {flash && (
          <div className="mb-6">
            <Alert type="success" onClose={() => setFlash(null)}>{flash}</Alert>
          </div>
        )}
        {error && (
          <div className="mb-6">
            <Alert type="error">{error}</Alert>
          </div>
        )}

        <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <section className="lg:col-span-1">
            <h2 className="mb-4 text-lg font-semibold">Registrar hotel</h2>
            <div className="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
              {loadingCatalogs ? (
                <p className="text-sm text-slate-500">Cargando catálogos…</p>
              ) : (
                <HotelForm cities={catalogs.cities} onCreated={handleCreated} />
              )}
            </div>
          </section>

          <section className="lg:col-span-2">
            <h2 className="mb-4 text-lg font-semibold">Hoteles registrados</h2>
            <HotelList
              hotels={hotels}
              loading={loading}
              onAssign={setSelected}
              onDelete={handleDelete}
            />
          </section>
        </div>
      </main>

      <Modal
        open={Boolean(selected)}
        title={selected ? `Asignar habitaciones · ${selected.name}` : ''}
        onClose={() => setSelected(null)}
      >
        {selected && (
          <RoomAssignmentForm
            hotel={selected}
            catalogs={catalogs}
            onAssigned={handleAssigned}
          />
        )}
      </Modal>
    </div>
  );
}
