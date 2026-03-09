import 'package:flutter/material.dart';
import '../../services/auth_service.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Home'),
      ),
      body: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text('Welcome to MyLaboAccess'),

            const SizedBox(height: 24),

            // Show Admin Panel button only if the stored role is admin
            FutureBuilder<bool>(
              future: AuthService().isAdmin(),
              builder: (context, snapshot) {
                if (snapshot.connectionState != ConnectionState.done) {
                  return const SizedBox.shrink();
                }

                if (snapshot.hasData && snapshot.data == true) {
                  return ElevatedButton(
                    onPressed: () async {
                      // Extra-check before navigation
                      if (await AuthService().isAdmin()) {
                        Navigator.pushNamed(context, '/admin');
                      } else {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Accès refusé')),
                        );
                      }
                    },
                    child: const Text('Admin Panel'),
                  );
                }

                return const SizedBox.shrink();
              },
            ),
          ],
        ),
      ),
    );
  }
}