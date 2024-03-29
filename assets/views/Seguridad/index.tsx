import React, { useState, useEffect, useCallback } from 'react'
import Title from '../../components/Title'
import InputCustumer from '../../components/InputCostumer'
import InputLabel from '../../components/InputLabel'
import { useSelector, useDispatch } from 'react-redux'
import { RootState } from '../../redux/reducers/index'
import { getProfileData, setProfileData, setNewClientPassword } from '../../redux/actions/'
import { ToastContainer, toast } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'
import { datos } from './data'
import {
  Content,
  ContentCard,
  Card,
  CardContent1,
  CardContent2,
  CardContent3,
  CardTitle,
  ContentInput,
  ContentInputCard,
  InputGroup,
  ContentInputTotal,
  CardHeaderTitle,
  CardContentButtonSeguridad
} from '../Integraciones/styles'
import InputNoEditable from '../../components/InputNoEditable'
import InputSelectWithValue from '../../components/InputSelectWithValue'
import InputSelectPaisWithValue from '../../components/InputSelectPaisWithValue'
import ButtonSpinner from '../../components/Button'

const breadcrumbTitle = [
  {
    title: 'Inicio',
    path: '/dashboard',
    active: true,
  },
  {
    title: 'Seguridad',
    path: '/seguridad',
    active: false,
  },
]

const dataSelect = [
  {
    value: 'movil',
    label: 'móvil',
  },
  {
    value: 'fijo',
    label: 'fijo',
  },
]
const Seguridad = ({setBreadcrumb}:any) => {
  const dispatch = useDispatch()

  const myStore: any = useSelector((state) => state)
  const setProfile = myStore.profilePost
  const updatePassword = myStore.setPassword
  const viewState: RootState = useSelector((state: RootState) => state)
  const nombreGuardado = viewState.profile.profileData.data.socialName
  const emailGuardado = viewState.profile.profileData.data.emailTransaction
  const nombreEmpresaGuardado = viewState.profile.profileData.data.companyName
  const numMovilGuardado = viewState.profile.profileData.data.cellPhone
  const numFijoGuardado = viewState.profile.profileData.data.mobilePhone
  const tipoTelefonoGuardado = viewState.profile.profileData.data.cellPhone != '' ? 'movil' : 'fijo'
  const indicativoCiudadGuardado = viewState.profile.profileData.data.indicativeCity
  const indicativoPaisGuardado = viewState.profile.profileData.data.indicativeCountry
  const webGuardado = viewState.profile.profileData.data.domain

  const [openCardContent, setOpenCardContent] = useState({ display: 'block' })
  const [openCardContent2, setOpenCardContent2] = useState({ display: 'block' })
  const [openCardContent3, setOpenCardContent3] = useState({ display: 'block' })
  const [nombre, setNombre] = useState('')
  const [email, setEmail] = useState('')
  const [nombreEmpresa, setNombreEmpresa] = useState('')
  const [numMovil, setNumMovil] = useState('')
  const [numFijo, setNumFijo] = useState('')
  const [indicativoCiudad, setIndicativoCiudad] = useState('')
  const [indicativoPais, setIndicativoPais] = useState('')
  const [tipoTelefono, setTipoTelefono] = useState('')
  const [web, setWeb] = useState('')
  const [password, setPassword] = useState('')
  const [newPassword, setNewPassword] = useState('')
  const [confirmPassword, setConfirmPassword] = useState('')
  const [loadingButton1, setLoadingButton1] = useState(false)
  const [loadingButton2, setLoadingButton2] = useState(false)

  useEffect(() => {
    setBreadcrumb(breadcrumbTitle)
  },[])

  useEffect(() => {
    setNombre(nombreGuardado)
    setEmail(emailGuardado)
    setNombreEmpresa(nombreEmpresaGuardado)
    setNumMovil(numMovilGuardado)
    setNumFijo(numFijoGuardado)
    setTipoTelefono(tipoTelefonoGuardado)
    setIndicativoCiudad(indicativoCiudadGuardado)
    setIndicativoPais(indicativoPaisGuardado)
    setWeb(webGuardado)
  }, [nombreGuardado, emailGuardado])

  useEffect(() => {
    dispatch(getProfileData())
  }, [])

  useEffect(() => {
    validateSetProfile()
  }, [setProfile])

  useEffect(() => {
    validateSetPassword()
  }, [updatePassword])

  const changeNombreEmpresa = useCallback((event) => {
    setNombreEmpresa(event)
  }, [])

  const changeNumMovil = useCallback((event) => {
    setNumMovil(event)
  }, [])

  const changeNumFijo = useCallback((event) => {
    setNumFijo(event)
  }, [])

  const changeIndCiudad = useCallback((event) => {
    setIndicativoCiudad(event)
  }, [])

  const changeIndPais = useCallback((event) => {
    setIndicativoPais(event)
  }, [])

  const changeTipoTelefono = useCallback((event) => {
    setTipoTelefono(event)
  }, [])

  const changeWeb = useCallback((event) => {
    setWeb(event)
  }, [])

  const changePassword = useCallback((event) => {
    setPassword(event)
  }, [])

  const changeNewPassword = useCallback((event) => {
    setNewPassword(event)
  }, [])

  const changeConfirmPassword = useCallback((event) => {
    setConfirmPassword(event)
  }, [])

  const validateSetProfile = () => {
    setLoadingButton1(false)
    if (setProfile.profileData.status == true) {
      toast.success('Datos Actualizados Correctamente')
    }
    if (setProfile.profileData.status == false) {
      toast.error('Error al Actualizar los Datos')
    }
  }

  const validateSetPassword = () => {
    if (updatePassword.passwordData.status == true) {
      toast.success('Contraseña Actualizada Correctamente')
      setLoadingButton2(false)
    }
    if (updatePassword.passwordData.status == false) {
      toast.error('Error al Actualizar la Contraseña')
      setLoadingButton2(false)
    }
  }

  const savePerfil = () => {
    const datos = {
      nombreEmpresa: nombreEmpresa,
      dominio: web,
      celular: numMovil,
      telefono: numFijo,
      indicativoCiudad: indicativoCiudad,
      indicativoPais: indicativoPais,
    }

    dispatch(setProfileData(datos))
    setLoadingButton1(true)
  }

  const savePassword = () => {
    if (password == '') {
      toast.error('La Contraseña Actual es Requerida')
    } else {
      if (newPassword == '') {
        toast.error('La Nueva Contraseña es Requerida')
      } else {
        if (confirmPassword == '') {
          toast.error('La Confirmación de la Contraseña es Requerida')
        } else {
          if (newPassword != confirmPassword) {
            toast.error('Las Contraseñas no Coinciden')
          } else {
            if (
              newPassword.match(
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\-!#$%&'()*+,./<=>?@[\]_`{|}~])(?=.{8,})/i
              )
            ) {
              const datos = {
                password: password,
                newPassword: newPassword,
                newPasswordConfirmation: confirmPassword,
              }

              dispatch(setNewClientPassword(datos))
              setLoadingButton2(true)
            } else {
              toast.error(
                'Nueva Contraseña Inválida, debe contener mínimo: 8 caracteres, 1 letra mayúscula, 1 número, 1 caracter especial'
              )
            }
          }
        }
      }
    }
  }

  return (
    <div>
      <Title title={'Perfil'}></Title>

      <Content>
        <ToastContainer />
        <ContentCard>
          <Card >
            <CardContent1 theme={openCardContent} style={{border:"none"}} >
              <ContentInputTotal>
                <ContentInputCard>
                  <InputLabel label={'Nombre'} />
                  <InputNoEditable name={'nombre'} type={'text'} width={'96%'} value={nombre} />
                </ContentInputCard>
              </ContentInputTotal>
              <ContentInputTotal>
                <ContentInputCard>
                  <InputLabel label={'Email'} />
                  <InputNoEditable name={'email'} type={'text'} width={'96%'} value={email} />
                </ContentInputCard>
              </ContentInputTotal>
            </CardContent1>
          </Card>
          <Card>
            <CardHeaderTitle>
              <CardTitle>Datos de contacto</CardTitle>
            </CardHeaderTitle>

            <CardContent2 theme={openCardContent2}>
              <ContentInputTotal>
                <ContentInputCard>
                  <InputLabel label={'Nombre empresa'} />
                  <InputCustumer
                    name={'nombreEmpresa'}
                    type={'text'}
                    placeholder={'Nombre de la Empresa'}
                    width={'96%'}
                    value={nombreEmpresa}
                    onChange={(e: any) => {
                      changeNombreEmpresa(e)
                    }}
                  />
                </ContentInputCard>
              </ContentInputTotal>

              <ContentInputTotal>
                <ContentInputCard>
                  <InputLabel label={'Teléfono'} />
                  <InputGroup>
                    <InputSelectWithValue
                      name={'type_telefono'}
                      placeholder={tipoTelefono != null ? tipoTelefono : 'movil'}
                      width={'30%'}
                      dataSelect={dataSelect}
                      value={tipoTelefono}
                      onClick={() => {}}
                      onChange={(e: any) => {
                        changeTipoTelefono(e)
                      }}
                    />
                    {tipoTelefono == 'fijo' ? (
                      <InputCustumer
                        name={'indicativoCiudad'}
                        type={'number'}
                        placeholder={'Indicativo'}
                        width={'30%'}
                        value={indicativoCiudad}
                        onChange={(e: any) => {
                          changeIndCiudad(e)
                        }}
                      />
                    ): (
                      <InputSelectPaisWithValue
                        name={'indicativoPais'}
                        value={indicativoPais}
                        placeholder={'Indicativo'}
                        width={'30%'}
                        dataSelect={datos}
                        onClick={() => {}}
                        onChange={(e: any) => {
                          changeIndPais(e)
                        }}
                      />
                    )
                    }

                    {tipoTelefono == 'fijo' ? (
                      <InputCustumer
                        name={'telefono'}
                        type={'number'}
                        placeholder={'Telefono'}
                        width={'30%'}
                        value={numFijo}
                        onChange={(e: any) => {
                          changeNumFijo(e)
                        }}
                      />
                    ) : (
                      <InputCustumer
                        name={'telefono'}
                        type={'number'}
                        placeholder={'Telefono'}
                        width={'30%'}
                        value={numMovil}
                        onChange={(e: any) => {
                          changeNumMovil(e)
                        }}
                      />
                    ) 
                    }
                  </InputGroup>
                </ContentInputCard>
              </ContentInputTotal>

              <ContentInputTotal>
                <ContentInputCard>
                  <InputLabel label={'Sitio web'} />
                  <InputCustumer
                    name={'web'}
                    type={'text'}
                    placeholder={'Sitio Web'}
                    width={'96%'}
                    value={web}
                    onChange={(e: any) => {
                      changeWeb(e)
                    }}
                  />
                </ContentInputCard>
              </ContentInputTotal>
            </CardContent2>

            <CardContentButtonSeguridad theme={openCardContent2}>
              <ButtonSpinner
                text={'Guardar Información'}
                loading={loadingButton1}
                onClick={() => {
                  savePerfil()
                }}
                disabled={loadingButton1}
              ></ButtonSpinner>
            </CardContentButtonSeguridad>
          </Card>

          <Card style={{ marginBottom: '1rem' }}>
            <CardHeaderTitle>
              <CardTitle>Actualizar Contraseña</CardTitle>
            </CardHeaderTitle>

            <CardContent3 theme={openCardContent3}>
              <ContentInputTotal>
                <ContentInputCard>
                  <InputLabel label={'Contraseña Actual'} />
                  <InputCustumer
                    name={'password'}
                    type={'password'}
                    placeholder={''}
                    width={'96%'}
                    value={password}
                    onChange={(e: any) => {
                      changePassword(e)
                    }}
                  />
                </ContentInputCard>
              </ContentInputTotal>
              <ContentInputTotal>
                <ContentInputCard>
                  <InputLabel label={'Nueva Contraseña'} />
                  <InputCustumer
                    name={'newPassword'}
                    type={'password'}
                    placeholder={''}
                    width={'96%'}
                    value={newPassword}
                    onChange={(e: any) => {
                      changeNewPassword(e)
                    }}
                  />
                </ContentInputCard>
              </ContentInputTotal>
              <ContentInputTotal>
                <ContentInputCard>
                  <InputLabel label={'Confirmar Contraseña'} />
                  <InputCustumer
                    name={'confirmPassword'}
                    type={'password'}
                    placeholder={''}
                    width={'96%'}
                    value={confirmPassword}
                    onChange={(e: any) => {
                      changeConfirmPassword(e)
                    }}
                  />
                </ContentInputCard>
              </ContentInputTotal>
            </CardContent3>
            <CardContentButtonSeguridad
              theme={openCardContent2}
            >
              <ButtonSpinner
                text={'Cambiar Contraseña'}
                loading={loadingButton2}
                onClick={() => {
                  savePassword()
                }}
                disabled={loadingButton2}
              ></ButtonSpinner>
            </CardContentButtonSeguridad>
          </Card>
        </ContentCard>
      </Content>
    </div>
  )
}

export default Seguridad
