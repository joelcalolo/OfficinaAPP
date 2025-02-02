// src/pages/Usuarios.jsx

import { useEffect, useState } from 'react';
import { Container, Table, Button, Form, Modal, Alert } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import axios from 'axios';

const Usuarios = () => {
  const [show, setShow] = useState(false);
  const [usuarios, setUsuarios] = useState([]);
  const [usuario, setUsuario] = useState({ id: null, nome: '', email: '', senha: '', role: '', documento: '' });
  const [isEditing, setIsEditing] = useState(false);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);

  const handleClose = () => {
    setShow(false);
    setUsuario({ id: null, nome: '', email: '', senha: '', role: '', documento: '' });
    setIsEditing(false);
    setError(null);
    setSuccess(null);
  };
  const handleShow = () => setShow(true);

  const fetchUsuarios = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/usuarios');
      setUsuarios(response.data);
    } catch (err) {
      setError('Erro ao carregar usuários.');
      console.error(err);
    }
  };

  useEffect(() => {
    fetchUsuarios();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (isEditing) {
        await axios.put(`http://localhost:8000/api/usuarios/${usuario.id}`, usuario, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        setUsuarios(usuarios.map((u) => (u.id === usuario.id ? usuario : u)));
      } else {
        const response = await axios.post('http://localhost:8000/api/registrar', usuario, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        setUsuarios([...usuarios, { ...usuario, id: response.data.id }]);
      }
      setSuccess(isEditing ? 'Usuário atualizado com sucesso!' : 'Usuário registrado com sucesso!');
      handleClose();
    } catch (err) {
      setError('Erro ao registrar ou atualizar o usuário.');
      console.error(err);
    }
  };

  const handleEdit = (user) => {
    setUsuario(user);
    setIsEditing(true);
    handleShow();
  };

  const handleDelete = async (id) => {
    try {
      await axios.delete(`http://localhost:8000/api/usuarios/${id}`);
      setUsuarios(usuarios.filter((u) => u.id !== id));
      setSuccess('Usuário deletado com sucesso!');
    } catch (err) {
      setError('Erro ao deletar usuário.');
      console.error(err);
    }
  };

  return (
    <Container>
      <h1 className="mt-4">Usuários</h1>
      <Button variant="primary" onClick={handleShow}>
        Adicionar Usuário
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
            <th>Email</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          {usuarios.map((user) => (
            <tr key={user.id}>
              <td>{user.nome}</td>
              <td>{user.email}</td>
              <td>
                <Button variant="warning" onClick={() => handleEdit(user)}>Editar</Button>
                <Button variant="danger" onClick={() => handleDelete(user.id)}>Deletar</Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>{isEditing ? 'Editar Usuário' : 'Adicionar Usuário'}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form onSubmit={handleSubmit}>
            <Form.Group controlId="formNome">
              <Form.Label>Nome</Form.Label>
              <Form.Control
                type="text"
                placeholder="Digite o nome"
                value={usuario.nome}
                onChange={(e) => setUsuario({ ...usuario, nome: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formEmail">
              <Form.Label>Email</Form.Label>
              <Form.Control
                type="email"
                placeholder="Digite o email"
                value={usuario.email}
                onChange={(e) => setUsuario({ ...usuario, email: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formSenha">
              <Form.Label>Senha</Form.Label>
              <Form.Control
                type="password"
                placeholder="Digite a senha"
                value={usuario.senha}
                onChange={(e) => setUsuario({ ...usuario, senha: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group controlId="formRole">
              <Form.Label>Role</Form.Label>
              <Form.Control
                as="select"
                value={usuario.role}
                onChange={(e) => setUsuario({ ...usuario, role: e.target.value })}
                required
              >
                <option value="">Selecione uma opção</option>
                <option value="Admin">Admin</option>
                <option value="Secretário">Secretário</option>
                <option value="Técnico">Técnico</option>
                <option value="Cliente">Cliente</option>
                <option value="Gerente">Gerente</option>
              </Form.Control>
            </Form.Group>
            <Form.Group controlId="formDocumento">
              <Form.Label>Documento</Form.Label>
              <Form.Control
                type="text"
                placeholder="Digite o documento"
                value={usuario.documento}
                onChange={(e) => setUsuario({ ...usuario, documento: e.target.value })}
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

export default Usuarios;