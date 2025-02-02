// src/pages/Viaturas.jsx

import { useEffect, useState } from 'react';
import { Container, Table, Button, Form, Modal, Alert } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import axios from 'axios';

const Viaturas = () => {
  const [show, setShow] = useState(false);
  const [viaturas, setViaturas] = useState([]);
  const [clientes, setClientes] = useState([]);
  const [viatura, setViatura] = useState({
    id: null,
    marca: '',
    modelo: '',
    cor: '',
    tipo_avaria: '',
    estado: 'Em reparação',
    codigo_validacao: '',
    cliente_id: '',
  });
  const [isEditing, setIsEditing] = useState(false);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);

  const handleClose = () => {
    setShow(false);
    setViatura({
      id: null,
      marca: '',
      modelo: '',
      cor: '',
      tipo_avaria: '',
      estado: 'Em reparação',
      codigo_validacao: '',
      cliente_id: '',
    });
    setIsEditing(false);
    setError(null);
    setSuccess(null);
  };
  const handleShow = () => setShow(true);

  const fetchViaturas = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/viaturas');
      setViaturas(response.data);
    } catch (err) {
      setError('Erro ao carregar viaturas.');
      console.error(err);
    }
  };

  const fetchClientes = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/usuarios');
      const clientesFiltrados = response.data.filter((usuario) => usuario.role === 'Cliente');
      setClientes(clientesFiltrados);
    } catch (err) {
      setError('Erro ao carregar clientes.');
      console.error(err);
    }
  };

  useEffect(() => {
    fetchViaturas();
    fetchClientes();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (!viatura.codigo_validacao) {
        viatura.codigo_validacao = generateCodigoValidacao();
      }

      if (isEditing) {
        await axios.put(`http://localhost:8000/api/viaturas/${viatura.id}`, viatura, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        setViaturas(viaturas.map((v) => (v.id === viatura.id ? viatura : v)));
      } else {
        const response = await axios.post('http://localhost:8000/api/viaturas', viatura, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        setViaturas([...viaturas, { ...viatura, id: response.data.id }]);
      }
      setSuccess(isEditing ? 'Viatura atualizada com sucesso!' : 'Viatura criada com sucesso!');
      handleClose();
    } catch (err) {
      setError('Erro ao registrar ou atualizar a viatura.');
      console.error(err);
    }
  };

  const generateCodigoValidacao = () => {
    return `VAL-${Math.random().toString(36).substr(2, 9).toUpperCase()}`;
  };

  const handleEdit = async (id) => {
    try {
      const response = await axios.get(`http://localhost:8000/api/viaturas/${id}`);
      setViatura(response.data);
      setIsEditing(true);
      handleShow();
    } catch (err) {
      setError('Erro ao carregar viatura.');
      console.error(err);
    }
  };

  const handleDelete = async (id) => {
    try {
      await axios.delete(`http://localhost:8000/api/viaturas/${id}`);
      setViaturas(viaturas.filter((v) => v.id !== id));
      setSuccess('Viatura deletada com sucesso!');
    } catch (err) {
      setError('Erro ao deletar viatura.');
      console.error(err);
    }
  };

  return (
    <Container>
      <h1 className="mt-4">Viaturas</h1>
      <Button variant="primary" onClick={handleShow}>
        Adicionar Viatura
      </Button>
      <Link to="/dashboard">
        <Button variant="secondary" className="ms-2">
          Voltar ao Dashboard
        </Button>
      </Link>

      {success && <Alert variant="success">{success}</Alert>}
      {error && <Alert variant="danger">{error}</Alert>}

      <Table striped bordered hover className="mt-3">
        <thead>
          <tr>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Cor</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          {viaturas.map((v) => (
            <tr key={v.id}>
              <td>{v.marca}</td>
              <td>{v.modelo}</td>
              <td>{v.cor}</td>
              <td>
                <Button variant="warning" onClick={() => handleEdit(v.id)}>Editar</Button>
                <Button variant="danger" onClick={() => handleDelete(v.id)}>Deletar</Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>{isEditing ? 'Editar Viatura' : 'Adicionar Viatura'}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form onSubmit={handleSubmit}>
            <Form.Group controlId="formMarca">
              <Form.Label>Marca</Form.Label>
              <Form.Control
                type="text"
                placeholder="Digite a marca"
                value={viatura.marca}
                onChange={(e) => setViatura({ ...viatura, marca: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formModelo">
              <Form.Label>Modelo</Form.Label>
              <Form.Control
                type="text"
                placeholder="Digite o modelo"
                value={viatura.modelo}
                onChange={(e) => setViatura({ ...viatura, modelo: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formCor">
              <Form.Label>Cor</Form.Label>
              <Form.Control
                type="text"
                placeholder="Digite a cor"
                value={viatura.cor}
                onChange={(e) => setViatura({ ...viatura, cor: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formTipoAvaria">
              <Form.Label>Tipo de Avaria</Form.Label>
              <Form.Control
                type="text"
                placeholder="Digite o tipo de avaria"
                value={viatura.tipo_avaria}
                onChange={(e) => setViatura({ ...viatura, tipo_avaria: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formEstado">
              <Form.Label>Estado</Form.Label>
              <Form.Control
                as="select"
                value={viatura.estado}
                onChange={(e) => setViatura({ ...viatura, estado: e.target.value })}
              >
                <option value="Em reparação">Em reparação</option>
                <option value="Concluído">Concluído</option>
                <option value="Não Concluído">Não Concluído</option>
              </Form.Control>
            </Form.Group>
            <Form.Group controlId="formCodigoValidacao">
              <Form.Label>Código de Validação</Form.Label>
              <Form.Control
                type="text"
                placeholder="Código de validação"
                value={viatura.codigo_validacao}
                readOnly
              />
            </Form.Group>
            <Form.Group controlId="formClienteId">
              <Form.Label>Cliente</Form.Label>
              <Form.Control
                as="select"
                value={viatura.cliente_id}
                onChange={(e) => setViatura({ ...viatura, cliente_id: e.target.value })}
                required
              >
                <option value="">Selecione um cliente</option>
                {clientes.map((cliente) => (
                  <option key={cliente.id} value={cliente.id}>
                    {cliente.nome}
                  </option>
                ))}
              </Form.Control>
            </Form.Group>
            <Button variant="primary" type="submit">
              Salvar
            </Button>
          </Form>
        </Modal.Body>
      </Modal>
    </Container>
  );
};

export default Viaturas;