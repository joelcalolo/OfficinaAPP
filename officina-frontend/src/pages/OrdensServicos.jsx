import { useState, useEffect } from "react";
import axios from "axios";
import { Button, Modal, Table, Form } from "react-bootstrap";

function OrdensServico() {
  const [ordens, setOrdens] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [showDetalhesModal, setShowDetalhesModal] = useState(false);
  const [viaturaId, setViaturaId] = useState("");
  const [mecanicoId, setMecanicoId] = useState("");
  const [servicos, setServicos] = useState([]);
  const [newServico, setNewServico] = useState({ servico_id: "", quantidade: 1, preco_unitario: 0 });
  const [viaturas, setViaturas] = useState([]);
  const [mecanicos, setMecanicos] = useState([]);
  const [ordemDetalhes, setOrdemDetalhes] = useState(null);
  const [clientes, setClientes] = useState([]); // Lista de clientes para viaturas
  const [tecnicos, setTecnicos] = useState([]); // Lista de técnicos
  const [allServicos, setAllServicos] = useState([]); // Lista de serviços disponíveis

  const [detalhesOrdem, setDetalhesOrdem] = useState(null); // Detalhes da ordem selecionada



  // Carregar Ordens de Serviço
  useEffect(() => {
    axios
      .get("http://localhost:8000/api/ordens-servico")
      .then((response) => setOrdens(response.data))
      .catch((error) => console.error("Erro ao carregar ordens:", error));
  }, []);

  // Carregar Viaturas e Mecânicos
  useEffect(() => {
    axios
      .get("http://localhost:8000/api/viaturas")
      .then((response) => setViaturas(response.data))
      .catch((error) => console.error("Erro ao carregar viaturas:", error));

    axios
      .get("http://localhost:8000/api/usuarios?role=Técnico")
      .then((response) => setMecanicos(response.data))
      .catch((error) => console.error("Erro ao carregar mecânicos:", error));

    // Carregar serviços
    axios
    .get("http://localhost:8000/api/servicos")
    .then((response) => setAllServicos(response.data))
    .catch((error) => console.error("Erro ao carregar serviços:", error));
  }, []);

  // Abrir Modal de Detalhes
  const handleShowDetalhesModal = (ordemId) => {
    axios
      .get(`http://localhost:8000/api/ordens-servico/${ordemId}`)
      .then((response) => {
        setOrdemDetalhes(response.data);
        setShowDetalhesModal(true);
      })
      .catch((error) => console.error("Erro ao carregar detalhes da ordem:", error));
  };
  
  // Fechar Modal de Detalhes
  const handleCloseDetalhesModal = () => {
    setShowDetalhesModal(false);
    setOrdemDetalhes(null);
  };

  // Abrir Modal para Criar Ordem de Serviço
  const handleShowModal = () => setShowModal(true);
  const handleCloseModal = () => setShowModal(false);

  // Inserir nova Ordem de Serviço
  const handleSubmit = (e) => {
    e.preventDefault();
    const ordemData = {
      viatura_id: viaturaId,
      mecanico_id: mecanicoId,
      servicos: servicos,
    };

    axios
      .post("http://localhost:8000/api/ordens-servico", ordemData)
      .then((response) => {
        setOrdens([...ordens, response.data.ordem_servico]);
        handleCloseModal();
      })
      .catch((error) => console.error("Erro ao criar ordem:", error));
  };

  // Adicionar serviço à lista
  const addServico = () => {
    if (newServico.servico_id && newServico.quantidade > 0 && newServico.preco_unitario > 0) {
      setServicos([...servicos, newServico]);
      setNewServico({ servico_id: "", quantidade: 1, preco_unitario: 0 });
    }
  };

  return (
    <div className="container">
      <h1>Ordens de Serviço</h1>
      <Button variant="primary" onClick={handleShowModal}>
        Criar Nova Ordem
      </Button>

      {/* Tabela com lista de ordens */}
      <Table striped bordered hover className="mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Viatura</th>
            <th>Mecânico</th>
            <th>Total</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          {ordens.map((ordem) => (
            <tr key={ordem.id}>
              <td>{ordem.id}</td>
              <td>{ordem.viatura.modelo}</td>
              <td>{ordem.mecanico.nome}</td>
              <td>{ordem.total} Kz</td>
              <td>
                <Button variant="info" onClick={() => handleShowDetalhesModal(ordem.id)}>
                  Ver Detalhes
                </Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      {/* Modal para Detalhes da Ordem de Serviço */}
      <Modal show={showDetalhesModal} onHide={handleCloseDetalhesModal}>
        <Modal.Header closeButton>
          <Modal.Title>Detalhes da Ordem de Serviço</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          {ordemDetalhes && (
            <>
              <p><strong>ID:</strong> {ordemDetalhes.id}</p>
              <p><strong>Viatura:</strong> {ordemDetalhes.viatura.modelo} ({ordemDetalhes.viatura.cliente_id})</p>
              <p><strong>Mecânico:</strong> {ordemDetalhes.mecanico.nome}</p>
              <p><strong>Total:</strong> {ordemDetalhes.total} Kz</p>
              <h5>Serviços:</h5>
              <ul>
                {ordemDetalhes.servicos.map((servico, index) => (
                  <li key={index}>
                    {`Serviço ID: ${servico.servico_id}, Quantidade: ${servico.quantidade}, Preço: ${servico.preco_unitario} Kz`}
                  </li>
                ))}
              </ul>
            </>
          )}
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleCloseDetalhesModal}>Fechar</Button>
        </Modal.Footer>
      </Modal>

      {/* Modal para Criar Ordem de Serviço */}
      <Modal show={showModal} onHide={handleCloseModal}>
        <Modal.Header closeButton>
          <Modal.Title>Criar Ordem de Serviço</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form onSubmit={handleSubmit}>
            <Form.Group className="mb-3">
              <Form.Label>Viatura</Form.Label>
              <Form.Control as="select" value={viaturaId} onChange={(e) => setViaturaId(e.target.value)} required>
                <option value="">Selecione a Viatura</option>
                {viaturas.map((viatura) => (
                  <option key={viatura.id} value={viatura.id}>
                    {viatura.modelo} ({viatura.dono})
                  </option>
                ))}
              </Form.Control>
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Mecânico</Form.Label>
              <Form.Control as="select" value={mecanicoId} onChange={(e) => setMecanicoId(e.target.value)} required>
                <option value="">Selecione o Mecânico</option>
                {mecanicos.map((mecanico) => (
                  <option key={mecanico.id} value={mecanico.id}>
                    {mecanico.nome}
                  </option>
                ))}
              </Form.Control>
            </Form.Group>

            {/* Adicionar Serviços à Ordem */}
            <h5>Adicionar Serviços</h5>
            <Form.Group className="mb-3">
              <Form.Label>Serviço</Form.Label>
              <Form.Control
                type="number"
                placeholder="Serviço ID"
                value={newServico.servico_id}
                onChange={(e) => setNewServico({ ...newServico, servico_id: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Quantidade</Form.Label>
              <Form.Control
                type="number"
                value={newServico.quantidade}
                onChange={(e) => setNewServico({ ...newServico, quantidade: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Preço Unitário</Form.Label>
              <Form.Control
                type="number"
                value={newServico.preco_unitario}
                onChange={(e) => setNewServico({ ...newServico, preco_unitario: e.target.value })}
                required
              />
            </Form.Group>
            <Button variant="secondary" onClick={addServico}>
              Adicionar Serviço
            </Button>

            <div className="mt-3">
              <ul>
                {servicos.map((servico, index) => (
                  <li key={index}>
                    {`Serviço ID: ${servico.servico_id}, Quantidade: ${servico.quantidade}, Preço: ${servico.preco_unitario}`}
                  </li>
                ))}
              </ul>
            </div>

            <Button variant="primary" type="submit" className="mt-3">
              Criar Ordem
            </Button>
          </Form>
        </Modal.Body>
      </Modal>
    </div>
  );
}

export default OrdensServico;
