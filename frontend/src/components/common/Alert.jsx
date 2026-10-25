/**
 * Mensaje de alerta reutilizable (éxito / error / info).
 */
export default function Alert({ type = 'info', children, onClose }) {
  if (!children) return null;

  const styles = {
    success: 'bg-green-50 text-green-800 border-green-200',
    error: 'bg-red-50 text-red-800 border-red-200',
    info: 'bg-blue-50 text-blue-800 border-blue-200',
  };

  return (
    <div
      role="alert"
      className={`flex items-start justify-between gap-3 rounded-md border px-4 py-3 text-sm ${styles[type]}`}
    >
      <span>{children}</span>
      {onClose && (
        <button
          type="button"
          onClick={onClose}
          aria-label="Cerrar alerta"
          className="shrink-0 font-bold opacity-60 hover:opacity-100"
        >
          ×
        </button>
      )}
    </div>
  );
}
