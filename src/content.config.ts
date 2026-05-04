import { defineCollection, z } from "astro:content";
import { glob } from "astro/loaders";

const examenesCollection = defineCollection({
  loader: glob({ pattern: "**/*.md", base: "./src/content/examenes" }),
  schema: z.object({
    title: z
      .string()
      .max(80, "El título es demasiado largo. Máximo 80 caracteres."),
    description: z
      .string()
      .max(160, "La descripción es demasiado larga. Máximo 160 caracteres."),
    pubDate: z.date().optional(),
    category: z
      .enum([
        "General",
        "Pruebas Virales",
        "Citometría",
        "Inmunodiagnóstico",
        "Inmunogenética",
      ])
      .default("General"),
    tipoExamen: z.string().max(50, "El tipo de examen es demasiado largo."),
    disabled: z.boolean().default(false),
  }),
});

export const collections = {
  examenes: examenesCollection,
};
