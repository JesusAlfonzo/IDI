// 1. IMPORTACIONES DE ASTRO:CONTENT
// `defineCollection` permite definir un esquema para una colección de contenido.
// `z` es la librería de validación de esquemas Zod, que Astro usa para validar el frontmatter de los archivos Markdown/MDX.
import { defineCollection, z } from "astro:content";

// 2. DEFINICIÓN DE LA COLECCIÓN 'EXAMENES'
// Aquí se define una colección llamada 'examenes'. Cada archivo dentro de `src/content/examenes/` será validado contra este esquema.
const examenesCollection = defineCollection({
  // `type: 'content'` especifica que esta colección contendrá archivos de contenido como Markdown (.md) o MDX (.mdx).
  type: "content",

  // `schema` define la estructura y las validaciones para el frontmatter de cada archivo en la colección.
  schema: z.object({
    // `title`: El título del examen.
    title: z
      .string()
      .max(80, "El título es demasiado largo. Máximo 80 caracteres."), // Debe ser un string con un máximo de 80 caracteres.

    // `description`: Una breve descripción del examen.
    description: z
      .string()
      .max(160, "La descripción es demasiado larga. Máximo 160 caracteres."), // Debe ser un string con un máximo de 160 caracteres.

    // `pubDate`: La fecha de publicación. Es opcional.
    pubDate: z.date().optional(),

    // `category`: La categoría a la que pertenece el examen.
    // Utiliza `z.enum` para restringir los valores a una lista predefinida.
    category: z
      .enum([
        "General",
        "Pruebas Virales",
        "Citometría",
        "Inmunodiagnóstico",
        "Inmunogenética",
      ])
      .default("General"), // Si no se especifica, el valor por defecto será "General".

    // `tipoExamen`: Un campo adicional para especificar el tipo de examen.
    tipoExamen: z.string().max(50, "El tipo de examen es demasiado largo."), // Debe ser un string con un máximo de 50 caracteres.

    // `price`: Campo de precio comentado. Si se descomenta, validaría que el precio sea un número positivo.
    // price: z.number().positive('El precio debe ser un número positivo.'),
  }),
});

// 3. EXPORTACIÓN DE LAS COLECCIONES
// Se exporta un objeto `collections` que registra todas las colecciones definidas.
// La clave ('examenes') debe coincidir con el nombre del directorio en `src/content/`.
export const collections = {
  examenes: examenesCollection,
};
