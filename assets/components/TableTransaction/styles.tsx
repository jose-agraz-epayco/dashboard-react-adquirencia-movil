import styled from 'styled-components'

export const StyledTable = styled.table`
  caption-side: top;
  border: none;
  border-collapse: collapse;
  width: 57vw;
  margin: 1vw;
  height: 10vw;
  /* border-collapse: separate; */
  /* border-spacing: 5px 10px; */

  caption-side: bottom;
  /* empty-cell: show | hide;  */
  /* empty-cell is a property of table or the cells themselves */

  /* vertical-align: baseline | sub | super | text-top | 
                text-bottom | middle | top | bottom | 
                <percentage> | <length> */

  /* tbody {
    vertical-align: top;
  }              */
  td,
  th {
    border: none;
    height: 3vw;
  }
  /* td,
  th {
    border: 1px solid;
  } */

  td {
    padding: 5px 10px;
  }

  tbody tr {
    :nth-of-type(odd) {
      background-color: #f9f9f9;
    }
    :hover {
      background-color: #ceecf5;
    }
  }
  thead > tr {
    background-color: #f1f1f1;
  }
  caption {
    font-size: 0.9em;
    padding: 5px;
    font-weight: bold;
  }
  header {
    font-family: Segoe UI;
    font-style: normal;
    font-weight: bold;
    font-size: 14px;
    line-height: 19px;
    color: #000000;
  }
  body {
    background: none;
    text-align: center;
    font-family: Segoe UI;
    font-style: normal;
    font-weight: normal;
    font-size: 13px;
    line-height: 17px;
  }
`

export const TableTextMoneda = styled.h2`
  font-weight: bold;
  font-size: 1vw;
`

export const TableTextStatusOK = styled.h1`
  color: green;
  font-size: 1vw;
`

export const TableTextStatusPending = styled.h1`
  color: orange;
  font-size: 1vw;
`

export const TableTextStatusCancel = styled.h1`
  font-size: 1vw;
  color: #ff6660;
`

export const TableTextStatusOther = styled.h1`
  font-size: 1vw;
`

export const ContentAction = styled.div`
  display: flex;
  justify-content: center;
  align-items: center;
`
export const ModalInput = styled.div`
  margin: 1rem;
  p {
    margin: 0;
    font-size: 13px;
    font-weight: 600;
  }
  input {
    width: 96%;
    padding: 0.4rem;
    font-size: 1rem;
  }
`
export const ModalButtons = styled.div`
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 1rem;
  padding: 0.5rem;
  .buttonSend {
    background-color: #1c0e49;
    color: white;
    padding: 0.5rem;
    border-radius: 4px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    &:hover {
      background-color: #312652;
    }
  }
  .buttonCancel {
    background-color: white;
    color: #1c0e49;
    padding: 0.5rem;
    border-radius: 4px;
    font-weight: bold;
    border: 1px solid #1c0e49;
    cursor: pointer;
    &:hover {
      background-color: #1c0e49;
      color: white;
    }
  }
`