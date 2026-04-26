import 'package:flutter/material.dart';
import 'pages/login_page.dart';
import 'pages/signup_page.dart';
import 'pages/admin_panel.dart';
import 'models/user.dart';
import 'services/api_service.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Flutter Demo',
      theme: ThemeData(
        brightness: Brightness.light,
        primaryColor: Colors.white,
        textTheme: const TextTheme(
          bodyLarge: TextStyle(
              fontFamily: 'Roboto', fontSize: 18, color: Colors.black87),
          bodyMedium: TextStyle(
              fontFamily: 'Roboto', fontSize: 16, color: Colors.black54),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.redAccent,
            foregroundColor: Colors.white,
            textStyle: const TextStyle(
              fontFamily: 'Roboto',
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
            padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 20),
          ),
        ),
      ),
      initialRoute: '/',
      routes: {
        '/': (context) => const LoginPage(),
        '/home': (context) => const MyHomePage(title: 'MyLaboAccess'),
        '/signup': (context) => const SignupPage(),
        '/admin': (context) => const AdminPanel(),
      },
    );
  }
}

class MyHomePage extends StatefulWidget {
  const MyHomePage({super.key, required this.title});

  final String title;

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  final List<Equipment> equipments = [
    Equipment('Écrans', 15),
    Equipment('Routeurs', 8),
    Equipment('Switches', 12),
    Equipment('Serveurs', 4),
    Equipment('Câbles réseau', 150),
    Equipment('Points d\'accès WiFi', 6),
  ];

  @override
  Widget build(BuildContext context) {
    final user = ModalRoute.of(context)?.settings.arguments;
    String userEmail = '';
    UserRole? role;

    if (user != null && user is User) {
      userEmail = user.email;
      role = user.role;
    }

    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            if (userEmail.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(right: 16.0),
                child: Text(userEmail,
                    style: const TextStyle(fontSize: 16, color: Colors.black54)),
              ),
            Text(widget.title,
                style: const TextStyle(fontFamily: 'Roboto', fontSize: 22)),
          ],
        ),
        backgroundColor: Theme.of(context).primaryColor,
        elevation: 2,
        actions: [
          if (role == UserRole.admin)
            IconButton(
              icon: Icon(Icons.admin_panel_settings,
                  color: Colors.redAccent.shade700),
              tooltip: 'Panneau Administrateur',
              onPressed: () {
                Navigator.pushNamed(context, '/admin', arguments: user);
              },
            ),
          IconButton(
            icon: Icon(Icons.report_problem, color: Colors.redAccent.shade700),
            tooltip: 'Signaler dégradation matériel',
            onPressed: () {
              String? selectedEquipment;
              int quantity = 1;
              TextEditingController descController = TextEditingController();

              showDialog(
                context: context,
                builder: (context) => AlertDialog(
                  title: const Text('Signaler une dégradation'),
                  content: StatefulBuilder(
                    builder: (context, setState) {
                      return Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          DropdownButtonFormField<String>(
                            decoration: const InputDecoration(
                                labelText: 'Matériel dégradé'),
                            initialValue: selectedEquipment,
                            items: [
                              for (var eq in equipments)
                                DropdownMenuItem(
                                  value: eq.name,
                                  child: Text(eq.name),
                                ),
                            ],
                            onChanged: (val) =>
                                setState(() => selectedEquipment = val),
                          ),
                          const SizedBox(height: 8),
                          TextFormField(
                            decoration: const InputDecoration(
                                labelText: 'Quantité dégradée'),
                            keyboardType: TextInputType.number,
                            initialValue: '1',
                            onChanged: (val) =>
                                setState(() => quantity = int.tryParse(val) ?? 1),
                          ),
                          const SizedBox(height: 8),
                          TextFormField(
                            controller: descController,
                            decoration: const InputDecoration(
                                labelText: 'Description / Situation'),
                            maxLines: 3,
                          ),
                        ],
                      );
                    },
                  ),
                  actions: [
                    TextButton(
                      onPressed: () => Navigator.pop(context),
                      child: const Text('Fermer'),
                    ),
                    ElevatedButton(
                      onPressed: () async {
                        final result = await ApiService.sendReport(
                          userEmail,
                          selectedEquipment ?? '',
                          quantity,
                          descController.text,
                        );

                        if (!mounted) return;

                        Navigator.pop(context);

                        showDialog(
                          context: context,
                          builder: (context) => AlertDialog(
                            title: Text(result['success'] == true
                                ? 'Signalement envoyé'
                                : 'Erreur'),
                            content: Text(result['message'] ?? ''),
                            actions: [
                              TextButton(
                                  onPressed: () => Navigator.pop(context),
                                  child: const Text('OK'))
                            ],
                          ),
                        );
                      },
                      child: const Text('Envoyer'),
                    ),
                  ],
                ),
              );
            },
          ),
          PopupMenuButton<String>(
            icon: const Icon(Icons.settings, color: Colors.black87),
            itemBuilder: (context) => const [
              PopupMenuItem(value: 'delete', child: Text('Supprimer le compte')),
              PopupMenuItem(value: 'edit', child: Text('Modifier email/mot de passe')),
              PopupMenuItem(value: 'help', child: Text('Besoin d\'aide ?')),
            ],
            onSelected: (value) {
              if (value == 'delete') {
                if (userEmail.isEmpty || userEmail == 'invité') {
                  showDialog(
                    context: context,
                    builder: (context) => AlertDialog(
                      title: const Text('Erreur'),
                      content: const Text('Aucun compte valide connecté.'),
                      actions: [
                        TextButton(
                            onPressed: () => Navigator.pop(context),
                            child: const Text('OK'))
                      ],
                    ),
                  );
                } else {
                  TextEditingController pwdCtrl = TextEditingController();
                  bool isLoading = false;

                  showDialog(
                    context: context,
                    builder: (context) => StatefulBuilder(
                      builder: (context, setState) {
                        return AlertDialog(
                          title: const Text('Supprimer le compte'),
                          content: Column(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              const Text(
                                  'Confirmez la suppression en saisissant votre mot de passe.'),
                              const SizedBox(height: 8),
                              TextField(
                                controller: pwdCtrl,
                                decoration: const InputDecoration(
                                    labelText: 'Mot de passe'),
                                obscureText: true,
                              ),
                            ],
                          ),
                          actions: [
                            TextButton(
                                onPressed: () => Navigator.pop(context),
                                child: const Text('Annuler')),
                            ElevatedButton(
                              onPressed: isLoading
                                  ? null
                                  : () async {
                                      final pwd = pwdCtrl.text;

                                      if (pwd.isEmpty) {
                                        showDialog(
                                          context: context,
                                          builder: (context) => AlertDialog(
                                            title: const Text('Erreur'),
                                            content: const Text(
                                                'Veuillez saisir votre mot de passe.'),
                                            actions: [
                                              TextButton(
                                                  onPressed: () =>
                                                      Navigator.pop(context),
                                                  child: const Text('OK'))
                                            ],
                                          ),
                                        );
                                        return;
                                      }

                                      setState(() => isLoading = true);
                                      final result =
                                          await ApiService.deleteAccount(
                                              userEmail, pwd);
                                      setState(() => isLoading = false);

                                      if (!mounted) return;

                                      Navigator.pop(context);

                                      if (result['success'] == true) {
                                        showDialog(
                                          context: context,
                                          builder: (context) => AlertDialog(
                                            title: const Text('Supprimé'),
                                            content:
                                                Text(result['message'] ?? ''),
                                            actions: [
                                              TextButton(
                                                onPressed: () {
                                                  Navigator.pop(context);
                                                  Navigator
                                                      .pushReplacementNamed(
                                                          context, '/');
                                                },
                                                child: const Text('OK'),
                                              ),
                                            ],
                                          ),
                                        );
                                      } else {
                                        showDialog(
                                          context: context,
                                          builder: (context) => AlertDialog(
                                            title: const Text('Erreur'),
                                            content:
                                                Text(result['message'] ?? ''),
                                            actions: [
                                              TextButton(
                                                  onPressed: () =>
                                                      Navigator.pop(context),
                                                  child: const Text('OK'))
                                            ],
                                          ),
                                        );
                                      }
                                    },
                              child: isLoading
                                  ? const SizedBox(
                                      width: 16,
                                      height: 16,
                                      child: CircularProgressIndicator(
                                          strokeWidth: 2))
                                  : const Text('Supprimer'),
                            ),
                          ],
                        );
                      },
                    ),
                  );
                }
              }
            },
          ),
          IconButton(
            icon: const Icon(Icons.logout, color: Colors.black87),
            onPressed: () {
              Navigator.pushReplacementNamed(context, '/');
            },
          ),
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 16),
            const Text(
              'Liste des équipements:',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            Expanded(
              child: ListView.builder(
                itemCount: equipments.length,
                itemBuilder: (context, index) {
                  final eq = equipments[index];
                  return Card(
                    child: ListTile(
                      title: Text(eq.name),
                      trailing: Text('${eq.quantity}'),
                    ),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class Equipment {
  final String name;
  final int quantity;

  Equipment(this.name, this.quantity);
}
