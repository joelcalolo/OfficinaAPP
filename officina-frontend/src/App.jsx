import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Login from './pages/login';
import Dashboard from './pages/Dashboard';
import Usuarios from './pages/Usuarios';
import Viaturas from './pages/viaturas';
import Servicos from './pages/Servicos';
import ServicosPrestados from './pages/ServicosPrestados';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Login />} /> {/* Página de Login como principal */}
        <Route path="/dashboard" element={<Dashboard />} />
        <Route path="/usuarios" element={<Usuarios />} />
        <Route path="/viaturas" element={<Viaturas />} />
        <Route path="/servicos" element={<Servicos />} />
        <Route path="/servisosprestados" element={<ServicosPrestados />}/>
      </Routes>
    </Router>
  );
}

export default App;