// src/pages/Servicos.jsx

import { useEffect, useState } from 'react';
import { Container, Table, Button, Form, Modal, Alert } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import axios from 'axios';

const Servicos = () => {
  const [show, setShow] = useState(false);
  const [servicos, setServicos] = useState([]);
  const [servico, setServico] = useState({ id: null, nome: '', descricao: '', preco: 0 });
  const [isEditing, setIsEditing] = useState(false);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);

  const handleClose = () => {
    setShow(false);
    setServico({ id: null, nome: '', descricao: '', preco: 0 });
    setIsEditing(false);
    setError(null);
    setSuccess(null);
  };
  const handleShow = () => setShow(true);

  const fetchServicos = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/servicos');
      setServicos(response.data);
    } catch (err) {
      setError('Erro ao carregar serviços.');
      console.error(err);
    }
  };

  useEffect(() => {
    fetchServicos();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (isEditing) {
        await axios.put(`http://localhost:8000/api/servicos/${servico.id}`, servico, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        setServicos(servicos.map((s) => (s.id === servico.id ? servico : s)));
      } else {
        const response = await axios.post('http://localhost:8000/api/servicos', servico, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        setServicos([...servicos, { ...servico, id: response.data.id }]);
      }
      setSuccess(isEditing ? 'Serviço atualizado com sucesso!' : 'Serviço criado com sucesso!');
      handleClose();
    } catch (err) {
      setError('Erro ao registrar ou atualizar o serviço.');
      console.error(err);
    }
  };

  const handleEdit = async (id) => {
    try {
      const response = await axios.get(`http://localhost:8000/api/servicos/${id}`);
      setServico(response.data);
      setIsEditing(true);
      handleShow();
    } catch (err) {
      setError('Erro ao carregar serviço.');
      console.error(err);
    }
  };

  const handleDelete = async (id) => {
    try {
      await axios.delete(`http://localhost:8000/api/servicos/${id}`);
      setServicos(servicos.filter((s) => s.id !== id));
      setSuccess('Serviço deletado com sucesso!');
    } catch (err) {
      setError('Erro ao deletar serviço.');
      console.error(err);
    }
  };

  return (
    <Container>
      <h1 className="mt-4">Serviços</h1>
      <Button variant="primary" onClick={handleShow}>
        Adicionar Serviço
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
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          {servicos.map((s) => (
            <tr key={s.id}>
              <td>{s.nome}</td>
              <td>{s.descricao}</td>
              <td>{s.preco.toFixed(2)}</td>
              <td>
                <Button variant="warning" onClick={() => handleEdit(s.id)}>Editar</Button>
                <Button variant="danger" onClick={() => handleDelete(s.id)}>Deletar</Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>{isEditing ? 'Editar Serviço' : 'Adicionar Serviço'}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form onSubmit={handleSubmit}>
            <Form.Group controlId="formNome">
              <Form.Label>Nome</Form.Label>
              <Form.Control
                type="text"
                placeholder="Digite o nome do serviço"
                value={servico.nome}
                onChange={(e) => setServico({ ...servico, nome: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formDescricao">
              <Form.Label>Descrição</Form.Label>
              <Form.Control
                type="text"
                placeholder="Digite a descrição do serviço"
                value={servico.descricao}
                onChange={(e) => setServico({ ...servico, descricao: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formPreco">
              <Form.Label>Preço</Form.Label>
              <Form.Control
                type="number"
                placeholder="Digite o preço"
                value={servico.preco}
                onChange={(e) => setServico({ ...servico, preco: parseFloat(e.target.value) })}
                required
              />
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

export default Servicos;