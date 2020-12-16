import { Button, Container, Grid, makeStyles, TextField } from '@material-ui/core';
import { useEffect, useState } from 'react';
import { ReCaptcha, loadReCaptcha } from 'react-recaptcha-v3';
import axios from 'axios';

const useStyles = makeStyles(theme => ({
  control: {
    padding: theme.spacing(3)
  }
}))

const formInputs = [
  {
    label: 'Nombre',
    inputField: 'name',
    type: 'text'
  },
  {
    label: 'Email',
    inputField: 'email',
    type: 'email'
  },
  {
    label: 'Contraseña',
    inputField: 'password',
    type: 'password'
  }
];

function App() {
  const classes = useStyles();

  const [formValues, setFormValues] = useState({
    name: '',
    email: '',
    password: '',
  });
  const [captchaToken, setCaptchaToken] = useState(null);

  const register = async () => {
    try {
      const response = axios.post(`${process.env.REACT_APP_BASE_URL}/register`, {
        name: formValues.name,
        email: formValues.email,
        password: formValues.password,
        token: captchaToken,
      });
      console.log('User created');
      console.log(response.data);
    } catch (e) {
      console.log('Something went wrong :(');
    }
  }

  const verifyCallback = token => {
    console.log(token);
    setCaptchaToken(token);
  }

  useEffect(() => {
    loadReCaptcha(process.env.REACT_APP_GOOGLE_CAPTCHA_TOKEN, () => {})
  })

  return (
    <Container maxWidth={'sm'}>
      <h1 align={'center'}>Registro</h1>
      <Grid container spacing={3}>
        {
          formInputs.map((item, index) => (
            <Grid key={index} item sm={12} className={classes.control}>
              <TextField
                fullWidth
                label={item.label}
                name={item.inputField}
                type={item.type}
                onChange={e => {
                  setFormValues(prevState => {
                    return {
                      ...prevState,
                      [e.target.name]: e.target.value
                    }
                  })
                }}
              />
            </Grid>
          ))
        }
        <Button
          fullWidth
          type={'button'}
          variant={'contained'}
          color={'primary'}
          onClick={register}
        >
          Registrar
        </Button>
      </Grid>
      <ReCaptcha
        sitekey={process.env.REACT_APP_GOOGLE_CAPTCHA_TOKEN}
        action={'submit'}
        verifyCallback={verifyCallback}
      />
    </Container>
  );
}

export default App;
