from fastapi import FastAPI
import requests

app = FastAPI()

@app.get("/cep/{cep}")
def consulta_cep(cep: str):
    url = f"https://viacep.com.br/ws/{cep}/json/"
    r = requests.get(url)

    if r.status_code != 200:
        return {"erro": "Falha ao consultar ViaCEP"}

    dados = r.json()

    if "erro" in dados:
        return {"erro": "CEP não encontrado"}

    return {
        "cep": dados.get("cep"),
        "logradouro": dados.get("logradouro"),
        "bairro": dados.get("bairro"),
        "cidade": dados.get("localidade"),
        "estado": dados.get("uf")
    }