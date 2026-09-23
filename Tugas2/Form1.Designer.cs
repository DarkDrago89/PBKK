namespace CalculatorApp
{
    partial class Form1
    {
        private System.ComponentModel.IContainer components = null;

        private Label lblTitle;
        private Label lblExpression;
        private TextBox txtDisplay;
        private Button btn7, btn8, btn9, btnDivide;
        private Button btn4, btn5, btn6, btnMultiply;
        private Button btn1, btn2, btn3, btnMinus;
        private Button btn0, btnDecimal, btnClear, btnPlus;
        private Button btnEquals;

        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        private void InitializeComponent()
        {
            lblTitle = new Label();
            lblExpression = new Label();
            txtDisplay = new TextBox();
            btn7 = new Button(); btn8 = new Button(); btn9 = new Button(); btnDivide = new Button();
            btn4 = new Button(); btn5 = new Button(); btn6 = new Button(); btnMultiply = new Button();
            btn1 = new Button(); btn2 = new Button(); btn3 = new Button(); btnMinus = new Button();
            btn0 = new Button(); btnDecimal = new Button(); btnClear = new Button(); btnPlus = new Button();
            btnEquals = new Button();
            SuspendLayout();

            // lblTitle
            lblTitle.Text = "Calculator";
            lblTitle.Font = new Font("Segoe UI", 14F, FontStyle.Bold);
            lblTitle.AutoSize = true;
            lblTitle.Location = new Point(20, 15);

            // lblExpression - menampilkan ekspresi berjalan, mis. "10 +"
            lblExpression.Name = "lblExpression";
            lblExpression.Text = "";
            lblExpression.Font = new Font("Segoe UI", 10F);
            lblExpression.ForeColor = Color.Gray;
            lblExpression.TextAlign = ContentAlignment.MiddleRight;
            lblExpression.Location = new Point(20, 50);
            lblExpression.Size = new Size(300, 20);

            // txtDisplay
            txtDisplay.Name = "txtDisplay";
            txtDisplay.Text = "0";
            txtDisplay.Font = new Font("Segoe UI", 20F);
            txtDisplay.TextAlign = HorizontalAlignment.Right;
            txtDisplay.Location = new Point(20, 72);
            txtDisplay.Size = new Size(300, 40);
            txtDisplay.ReadOnly = true;

            // 4-column grid of buttons, starting at y=130
            int startX = 20, startY = 130, btnW = 65, btnH = 50, gap = 10;

            void PlaceGrid(Button b, int col, int row, string text, EventHandler handler)
            {
                b.Text = text;
                b.Font = new Font("Segoe UI", 12F);
                b.Location = new Point(startX + col * (btnW + gap), startY + row * (btnH + gap));
                b.Size = new Size(btnW, btnH);
                b.Click += handler;
            }

            // Row 0: 7 8 9 ÷
            PlaceGrid(btn7, 0, 0, "7", NumberButton_Click);
            PlaceGrid(btn8, 1, 0, "8", NumberButton_Click);
            PlaceGrid(btn9, 2, 0, "9", NumberButton_Click);
            PlaceGrid(btnDivide, 3, 0, "÷", OperatorButton_Click);

            // Row 1: 4 5 6 ×
            PlaceGrid(btn4, 0, 1, "4", NumberButton_Click);
            PlaceGrid(btn5, 1, 1, "5", NumberButton_Click);
            PlaceGrid(btn6, 2, 1, "6", NumberButton_Click);
            PlaceGrid(btnMultiply, 3, 1, "×", OperatorButton_Click);

            // Row 2: 1 2 3 −
            PlaceGrid(btn1, 0, 2, "1", NumberButton_Click);
            PlaceGrid(btn2, 1, 2, "2", NumberButton_Click);
            PlaceGrid(btn3, 2, 2, "3", NumberButton_Click);
            PlaceGrid(btnMinus, 3, 2, "−", OperatorButton_Click);

            // Row 3: 0 . C +
            PlaceGrid(btn0, 0, 3, "0", NumberButton_Click);
            PlaceGrid(btnDecimal, 1, 3, ".", btnDecimal_Click);
            PlaceGrid(btnClear, 2, 3, "C", btnClear_Click);
            PlaceGrid(btnPlus, 3, 3, "+", OperatorButton_Click);

            // Row 4: = (spans full width)
            btnEquals.Text = "=";
            btnEquals.Font = new Font("Segoe UI", 12F, FontStyle.Bold);
            btnEquals.Location = new Point(startX, startY + 4 * (btnH + gap));
            btnEquals.Size = new Size(4 * btnW + 3 * gap, btnH);
            btnEquals.Click += btnEquals_Click;

            // Form1
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(340, startY + 5 * (btnH + gap) + 20);
            Controls.Add(lblTitle);
            Controls.Add(lblExpression);
            Controls.Add(txtDisplay);
            Controls.Add(btn7); Controls.Add(btn8); Controls.Add(btn9); Controls.Add(btnDivide);
            Controls.Add(btn4); Controls.Add(btn5); Controls.Add(btn6); Controls.Add(btnMultiply);
            Controls.Add(btn1); Controls.Add(btn2); Controls.Add(btn3); Controls.Add(btnMinus);
            Controls.Add(btn0); Controls.Add(btnDecimal); Controls.Add(btnClear); Controls.Add(btnPlus);
            Controls.Add(btnEquals);
            Text = "Calculator";
            FormBorderStyle = FormBorderStyle.FixedSingle;
            MaximizeBox = false;
            ResumeLayout(false);
            PerformLayout();
        }
    }
}
