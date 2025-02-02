// src/pages/Dashboard.jsx

import { Container, Navbar, Nav } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const Dashboard = () => {
  return (
    <Container fluid>
      <Navbar bg="light" expand="lg">
        <Navbar.Brand as={Link} to="/">Oficina App</Navbar.Brand>
        <Nav className="me-auto">
          <Nav.Link as={Link} to="/usuarios">Usuários</Nav.Link>
          <Nav.Link as={Link} to="/viaturas">Viaturas</Nav.Link>
          <Nav.Link as={Link} to="/servicos">Serviços</Nav.Link>
          <Nav.Link as={Link} to="/servisosprestados">Serviços Prestados</Nav.Link>
        </Nav>
      </Navbar>

      <h1 className="mt-4">Bem-vindo ao Dashboard!</h1>
      <p>Esta é a página inicial do painel administrativo.</p>

      {/* Você pode adicionar seções adicionais aqui */}
      <div className="mt-4">
        <h2>Estatísticas Rápidas</h2>
        <ul>
          <li>Total de Usuários: 10</li>
          <li>Total de Viaturas: 5</li>
          <li>Total de Serviços: 8</li>
          <li>Total de Pagamentos: 15</li>
        </ul>
      </div>
    </Container>
  );
};

export default Dashboard;