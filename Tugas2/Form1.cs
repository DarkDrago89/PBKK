namespace CalculatorApp
{
    public partial class Form1 : Form
    {
        double firstNumber = 0;
        double secondNumber = 0;
        double result = 0;
        string operation = "";

        public Form1()
        {
            InitializeComponent();
        }

        private void NumberButton_Click(object sender, EventArgs e)
        {
            Button button = (Button)sender;
            if (txtDisplay.Text == "0")
                txtDisplay.Text = button.Text;
            else
                txtDisplay.Text += button.Text;
        }

        private void OperatorButton_Click(object sender, EventArgs e)
        {
            Button button = (Button)sender;
            firstNumber = double.Parse(txtDisplay.Text);
            operation = button.Text;
            lblExpression.Text = $"{FormatNumber(firstNumber)} {operation}";
            txtDisplay.Clear();
        }

        private void btnEquals_Click(object sender, EventArgs e)
        {
            try
            {
                secondNumber = double.Parse(txtDisplay.Text);
                switch (operation)
                {
                    case "+": result = firstNumber + secondNumber; break;
                    case "−": result = firstNumber - secondNumber; break;
                    case "×": result = firstNumber * secondNumber; break;
                    case "÷":
                        if (secondNumber == 0)
                            throw new DivideByZeroException();
                        result = firstNumber / secondNumber;
                        break;
                }
                // Tampilkan ekspresi lengkap sebelum hasil, lalu kosongkan setelah dipakai
                lblExpression.Text = $"{FormatNumber(firstNumber)} {operation} {FormatNumber(secondNumber)} =";
                txtDisplay.Text = result.ToString();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message, "Error");
            }
        }

        private void btnClear_Click(object sender, EventArgs e)
        {
            firstNumber = 0;
            secondNumber = 0;
            result = 0;
            operation = "";
            lblExpression.Text = "";
            txtDisplay.Text = "0";
        }

        // Format angka agar tidak menampilkan ".0" yang tidak perlu (mis. 10 bukan 10.0)
        private static string FormatNumber(double value)
        {
            return value % 1 == 0 ? value.ToString("0") : value.ToString();
        }

        private void btnDecimal_Click(object sender, EventArgs e)
        {
            if (!txtDisplay.Text.Contains("."))
                txtDisplay.Text += ".";
        }
    }
}
