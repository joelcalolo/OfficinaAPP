// src/pages/RegistrarServicosPrestados.jsx

import { useEffect, useState } from 'react';
import { Container, Form, Button, Alert } from 'react-bootstrap';
import axios from 'axios';

const RegistrarServicosPrestados = () => {
  const [viaturaId, setViaturaId] = useState('');
  const [data, setData] = useState('');
  const [tecnico, setTecnico] = useState('');
  const [observacoes, setObservacoes] = useState('');
  const [servicos, setServicos] = useState([{ servico_id: '', quantidade: 0, valor_unitario: 0 }]);
  const [success, setSuccess] = useState(null);
  const [error, setError] = useState(null);

  const handleAddServico = () => {
    setServicos([...servicos, { servico_id: '', quantidade: 0, valor_unitario: 0 }]);
  };

  const handleServicoChange = (index, field, value) => {
    const newServicos = [...servicos];
    newServicos[index][field] = value;
    setServicos(newServicos);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost:8000/api/servicos-prestados', {
        viatura_id: viaturaId,
        data,
        tecnico,
        observacoes,
        servicos,
      }, {
        headers: {
          'Content-Type': 'application/json',
        },
      });
      setSuccess('Serviço prestado registrado com sucesso!');
      setError(null);
      // Reset the form
      setViaturaId('');
      setData('');
      setTecnico('');
      setObservacoes('');
      setServicos([{ servico_id: '', quantidade: 0, valor_unitario: 0 }]);
    } catch (err) {
      setError('Erro ao registrar serviço prestado.');
      console.error(err);
    }
  };

  return (
    <Container>
      <h1 className="mt-4">Registrar Serviço Prestado</h1>
      {success && <Alert variant="success">{success}</Alert>}
      {error && <Alert variant="danger">{error}</Alert>}

      <Form onSubmit={handleSubmit}>
        <Form.Group controlId="formViaturaId">
          <Form.Label>Viatura ID</Form.Label>
          <Form.Control
            type="number"
            value={viaturaId}
            onChange={(e) => setViaturaId(e.target.value)}
            required
          />
        </Form.Group>

        <Form.Group controlId="formData">
          <Form.Label>Data</Form.Label>
          <Form.Control
            type="datetime-local"
            value={data}
            onChange={(e) => setData(e.target.value)}
            required
          />
        </Form.Group>

        <Form.Group controlId="formTecnico">
          <Form.Label>Técnico</Form.Label>
          <Form.Control
            type="text"
            value={tecnico}
            onChange={(e) => setTecnico(e.target.value)}
            required
          />
        </Form.Group>

        <Form.Group controlId="formObservacoes">
          <Form.Label>Observações</Form.Label>
          <Form.Control
            type="text"
            value={observacoes}
            onChange={(e) => setObservacoes(e.target.value)}
          />
        </Form.Group>

        <h5>Serviços</h5>
        {servicos.map((servico, index) => (
          <div key={index} className="mb-3">
            <Form.Group controlId={`formServicoId${index}`}>
              <Form.Label>Serviço ID</Form.Label>
              <Form.Control
                type="number"
                value={servico.servico_id}
                onChange={(e) => handleServicoChange(index, 'servico_id', e.target.value)}
                required
              />
            </Form.Group>
            <Form.Group controlId={`formQuantidade${index}`}>
              <Form.Label>Quantidade</Form.Label>
              <Form.Control
                type="number"
                value={servico.quantidade}
                onChange={(e) => handleServicoChange(index, 'quantidade', e.target.value)}
                required
              />
            </Form.Group>
            <Form.Group controlId={`formValorUnitario${index}`}>
              <Form.Label>Valor Unitário</Form.Label>
              <Form.Control
                type="number"
                value={servico.valor_unitario}
                onChange={(e) => handleServicoChange(index, 'valor_unitario', e.target.value)}
                required
              />
            </Form.Group>
          </div>
        ))}
        <Button variant="secondary" onClick={handleAddServico}>
          Adicionar Serviço
        </Button>
        <Button variant="primary" type="submit" className="ms-2">
          Registrar
        </Button>
      </Form>
    </Container>
  );
};

export default RegistrarServicosPrestados;