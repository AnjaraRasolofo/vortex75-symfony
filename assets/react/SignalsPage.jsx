import { useMemo, useState } from "react";
import { createRoot } from "react-dom/client";

function SignalsPage({ initialSignals }) {
  const [signals] = useState(initialSignals || []);
  const [filter, setFilter] = useState("all");

  const filteredSignals = useMemo(() => {
    if (filter === "all") return signals;
    return signals.filter((signal) => signal.type === filter);
  }, [signals, filter]);

  const formatDate = (date) => {
    if (!date) return "-";

    return new Date(date).toLocaleDateString("fr-FR", {
      day: "2-digit",
      month: "short",
      year: "numeric",
    });
  };

  const getResultBadge = (result) => {
    switch (result) {
      case "win":
        return "bg-green-100 text-green-700";
      case "loss":
        return "bg-red-100 text-red-700";
      default:
        return "bg-slate-100 text-slate-700";
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 py-10">
      <div className="max-w-6xl mx-auto px-4">
        <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
          <div>
            <h1 className="text-3xl font-bold text-slate-900">
              Vortex75 - Signals
            </h1>
            <p className="text-slate-500 mt-1">
              Liste des signaux de trading publiés par l’équipe.
            </p>
          </div>

          <select
            value={filter}
            onChange={(e) => setFilter(e.target.value)}
            className="border border-slate-300 rounded-xl px-4 py-2 bg-white"
          >
            <option value="all">Tous les signaux</option>
            <option value="buy">Buy</option>
            <option value="sell">Sell</option>
          </select>
        </div>

        {filteredSignals.length === 0 && (
          <div className="bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-500">
            Aucun signal trouvé.
          </div>
        )}

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {filteredSignals.map((signal) => (
            <article
              key={signal.id}
              className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition"
            >
              {signal.graphic && (
                <img
                  src={`/uploads/${signal.graphic}`}
                  alt="Graphique du signal"
                  className="w-full h-56 object-cover"
                />
              )}

              <div className="p-5">
                <div className="flex items-center justify-between mb-3">
                  <span
                    className={`px-3 py-1 rounded-full text-sm font-semibold ${
                      signal.type === "buy"
                        ? "bg-green-100 text-green-700"
                        : "bg-red-100 text-red-700"
                    }`}
                  >
                    {signal.type?.toUpperCase()}
                  </span>

                  <span className="text-sm text-slate-500">
                    {formatDate(signal.pubishedAt)}
                  </span>
                </div>

                <h2 className="text-xl font-bold text-slate-900 mb-4">
                  Entry : {signal.entry} $
                </h2>

                <div className="grid grid-cols-2 gap-3 text-sm mb-4">
                  <InfoCard label="Stop Loss" value={`${signal.stopLoss} $`} />
                  <InfoCard label="Take Profit" value={`${signal.takeProfit} $`} />
                </div>

                <div className="flex items-center justify-between">
                  <span
                    className={`px-3 py-1 rounded-full text-sm font-medium ${getResultBadge(
                      signal.result
                    )}`}
                  >
                    {signal.result || "pending"}
                  </span>

                  <span className="text-sm text-slate-500">
                    Par {signal.auteur?.name || "Admin"}
                  </span>
                </div>
              </div>
            </article>
          ))}
        </div>
      </div>
    </div>
  );
}

function InfoCard({ label, value }) {
  return (
    <div className="bg-slate-50 rounded-xl p-3">
      <p className="text-slate-500 text-xs">{label}</p>
      <p className="font-semibold text-slate-900">{value}</p>
    </div>
  );
}
/*
const rootElement = document.getElementById("signals-root");

if (rootElement) {
  const signals = JSON.parse(rootElement.dataset.signals || "[]");

  createRoot(rootElement).render(
    <SignalsPage initialSignals={signals} />
  );
}*/